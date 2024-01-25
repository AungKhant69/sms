<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use App\Helper\FormHelper;
use App\Models\ClassModel;
use Illuminate\Http\Request;
use App\Models\AttendanceModel;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    public $pagination = '';
    public $data = [];
    public function __construct()
    {
        $config = FormHelper::getConfig();
        $this->pagination = $config['paginate'];
        $this->data = [
            'header_title' => 'Student Attendance',
            'getStudent' => [],
            'getRecord' => [],
        ];
    }

    public function StudentAttendance(Request $request)
    {
        $this->data['getClass'] = $this->getClass();

        if (!empty($request->get('class_id')) && !empty($request->get('attendance_date'))) {
            $this->data['getStudent'] = $this->getStudentClass($request->get('class_id'));

            // Fetch existing attendance records from the database
            $this->data['existingAttendance'] = AttendanceModel::whereIn('student_id', $this->data['getStudent']->pluck('id'))
                ->where('class_id', $request->get('class_id'))
                ->where('attendance_date', $request->get('attendance_date'))
                ->get();
        }

        return view('admin.student_management.attendance')->with([
            'data' => $this->data,
        ]);
    }

    public function StudentAttendance_teacher(Request $request)
    {
        $this->data['getClass'] = $this->getClass();

        if (!empty($request->get('class_id')) && !empty($request->get('attendance_date'))) {
            $this->data['getStudent'] = $this->getStudentClass($request->get('class_id'));

            // Fetch existing attendance records from the database
            $this->data['existingAttendance'] = AttendanceModel::whereIn('student_id', $this->data['getStudent']->pluck('id'))
                ->where('class_id', $request->get('class_id'))
                ->where('attendance_date', $request->get('attendance_date'))
                ->get();
        }

        return view('teacher.student_management.attendance')->with([
            'data' => $this->data,
        ]);
    }


    private function getClass()
    {
        $record = ClassModel::select('id', 'name')
            ->where('class.status', '=', '1')
            ->orderBy('class.name', 'asc')
            ->get();

        return $record;
    }

    private function getStudentClass($class_id)
    {
        return User::where('users.user_type', '=', 3)
            ->with('classData')
            ->where('users.class_id', $class_id)
            ->orderBy('users.id', 'desc')->get();
    }

    public function store_attendance(Request $request)
    {
        try {
            if (!empty($request->attendance_type)) {
                foreach ($request->attendance_type as $record) {
                    $attendance = !empty($record['attendance']) ? $record['attendance'] : null;

                    $existingRecord = $this->NoDuplicateRecord($request->class_id, $request->attendance_date, $request->student_id);

                    if (!empty($existingRecord)) {
                        // Update existing record
                        $existingRecord->update([
                            'attendance_type' => $attendance,
                            'updated_by' => Auth::user()->id,
                        ]);
                    } else {
                        AttendanceModel::create([
                            'class_id' => $request->class_id,
                            'attendance_date' => $request->attendance_date,
                            'student_id' => $request->student_id,
                            'attendance_type' => $attendance,
                            'created_by' => Auth::user()->id,
                        ]);
                    }
                }
            }

            return redirect()->back()->with('success', 'Attendance successfully stored.');
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function store_attendance_teacher(Request $request)
    {
        try {
            if (!empty($request->attendance_type)) {
                foreach ($request->attendance_type as $record) {
                    $attendance = !empty($record['attendance']) ? $record['attendance'] : null;

                    $existingRecord = $this->NoDuplicateRecord($request->class_id, $request->attendance_date, $request->student_id);

                    if (!empty($existingRecord)) {
                        // Update existing record
                        $existingRecord->update([
                            'attendance_type' => $attendance,
                            'updated_by' => Auth::user()->id,
                        ]);
                    } else {
                        AttendanceModel::create([
                            'class_id' => $request->class_id,
                            'attendance_date' => $request->attendance_date,
                            'student_id' => $request->student_id,
                            'attendance_type' => $attendance,
                            'created_by' => Auth::user()->id,
                        ]);
                    }
                }
            }

            return redirect()->back()->with('success', 'Attendance successfully stored.');
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    private function NoDuplicateRecord($class_id, $attendance_date, $student_id)
    {
        return AttendanceModel::with('student', 'classData')
            ->where('class_id', $class_id)
            ->where('attendance_date', $attendance_date)
            ->where('student_id', $student_id)->first();
    }

    // Attendance Report

    public function AttendanceReport(Request $request)
    {
        $this->data['getClass'] = $this->getClass();
        $this->data['getRecord'] = $this->getRecord($request);

        $this->data['header_title'] = 'Attendance Report';
        return view('admin.student_management.attendance_report')->with([
            'data' => $this->data,
        ]);
    }

    public function AttendanceReport_teacher(Request $request)
    {
        $this->data['getClass'] = $this->getClass();
        $this->data['getRecord'] = $this->getRecord($request);

        $this->data['header_title'] = 'Attendance Report';
        return view('teacher.student_management.attendance_report')->with([
            'data' => $this->data,
        ]);
    }

    private function getRecord(Request $request)
    {
        $query =  AttendanceModel::with('classData', 'student');

        if (!empty($request->get('class_id'))) {
            $query = $query->where('attendance.class_id', '=', $request->get('class_id'));
        }

        if (!empty($request->get('attendance_date'))) {
            $query = $query->where('attendance.attendance_date', '=', $request->get('attendance_date'));
        }

        if (!empty($request->get('attendance_type'))) {
            $query = $query->where('attendance.attendance_type', '=', $request->get('attendance_type'));
        }

        // Add a condition for the student relationship
        if (!empty($request->get('student_name'))) {
            $query = $query->whereHas('student', function ($subQuery) use ($request) {
                $subQuery->where('users.name', 'LIKE', '%' . $request->get('student_name') . '%');
            });
        }

        $paginator = $query->orderBy('id', 'desc')->paginate($this->pagination);
        return $paginator;
    }

    // student side
    public function AttendanceReport_student()
    {
        $this->data['getRecord'] = $this->getRecordStudent(Auth::user()->id);

        $this->data['header_title'] = 'My Attendance Report';
        return view('student.my_academic_info.attendance_report')->with([
            'data' => $this->data,
        ]);
    }

    private function getRecordStudent($student_id)
    {
        $attendance = AttendanceModel::where('student_id', $student_id)->with('student', 'classData');

        $paginator = $attendance->orderBy('id', 'desc')->paginate($this->pagination);
        return $paginator;
    }
}
