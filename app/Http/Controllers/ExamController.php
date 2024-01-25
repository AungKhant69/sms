<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use App\Models\ExamModel;
use App\Helper\FormHelper;
use App\Models\ClassModel;
use Illuminate\Http\Request;
use App\Models\ClassSubjectModel;
use App\Models\ExamScheduleModel;
use App\Models\MarksRegisterModel;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreExamRequest;
use App\Models\AssignClassTeacherModel;
use App\Http\Requests\UpdateExamRequest;
use App\Http\Requests\ExamScheduleStoreRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ExamController extends Controller
{
    public $pagination = '';
    public $data = [];
    public function __construct()
    {
        $config = FormHelper::getConfig();
        $this->pagination = $config['paginate'];
        $this->data = [
            'header_title' => 'Exams List',
            'getRecord' => [],
        ];
    }

    public function index(Request $request)
    {
        $this->data['getRecord'] = $this->getExam($request);
        return view('admin.examinations.exam.list')->with([
            'data' => $this->data,
        ]);
    }

    public function create()
    {
        $this->data['header_title'] = 'Add Exam';
        return view('admin.examinations.exam.add')->with([
            'data' => $this->data,
        ]);
    }

    public function store(StoreExamRequest $request)
    {
        $Exam = ExamModel::create([
            'name' => $request->name,
            'status' => $request->status,
            'created_by' => auth()->user()->id,
        ]);

        return redirect('/admin/examinations/exam/list')->with('success', 'Exam is Added Successfully');
    }

    public function edit($id)
    {
        $this->data['getRecord'] = ExamModel::findOrFail($id);
        if (!empty($this->data['getRecord'])) {
            $this->data['header_title'] = 'Edit Exam';
            return view('admin.examinations.exam.edit')->with([
                'data' => $this->data,
            ]);
        }
    }

    public function update(UpdateExamRequest $request, $id)
    {
        try {
            $Exam = ExamModel::findOrFail($id);

            $Exam->update([
                'name' => $request->name,
                'status' => $request->status,
                'updated_by' => auth()->user()->id,
            ]);

            return redirect('/admin/examinations/exam/list')->with('success', 'Exam is Updated Successfully');
        } catch (ModelNotFoundException $exception) {
            return redirect('/admin/examinations/exam/list')->with('error', $exception->getMessage());
        } catch (Exception $e) {
            return redirect('/admin/examinations/exam/list')->with('error', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $Exam = ExamModel::findOrFail($id);
            $Exam->delete();

            return redirect('/admin/examinations/exam/list')->with('success', 'Subject is soft Deleted Successfully');
        } catch (ModelNotFoundException $exception) {
            return redirect('/admin/examinations/exam/list')->with('error', $exception->getMessage());
        } catch (Exception $e) {
            return redirect('/admin/examinations/exam/list')->with('error', $e->getMessage());
        }
    }

    public function deletedList()
    {
        try {
            $softDeletedRecords = ExamModel::onlyTrashed()->get();

            return view('admin.examinations.exam.deleted_list', compact('softDeletedRecords'));
        } catch (ModelNotFoundException $exception) {
            return redirect('/admin/examinations/exam/list')->with('error', $exception->getMessage());
        } catch (Exception $e) {
            return redirect('/admin/examinations/exam/list')->with('error', $e->getMessage());
        }
    }

    public function restore($id)
    {
        try {
            ExamModel::withTrashed()->where('id', $id)->restore();

            return redirect('/admin/examinations/exam/list')->with('success', 'Exam data is restored successfully');
        } catch (ModelNotFoundException $exception) {
            return redirect('/admin/examinations/exam/list')->with('error', $exception->getMessage());
        } catch (Exception $e) {
            return redirect('/admin/examinations/exam/list')->with('error', $e->getMessage());
        }
    }

    public function forceDelete($id)
    {
        try {
            ExamModel::withTrashed()->where('id', $id)->forceDelete();

            return redirect('/admin/examinations/exam/list')->with('success', 'Exam data is permanently deleted');
        } catch (ModelNotFoundException $exception) {
            return redirect('/admin/examinations/exam/list')->with('error', $exception->getMessage());
        } catch (Exception $e) {
            return redirect('/admin/examinations/exam/list')->with('error', $e->getMessage());
        }
    }

    private function getExam(Request $request)
    {
        $query = ExamModel::with('createdBy', 'updatedBy');

        if ($request->name != '') {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        if ($request->date != '') {
            $query->whereDate('created_at', $request->date);
        }

        $paginator = $query->orderBy('id', 'desc')->paginate($this->pagination);

        $paginator->appends([
            'name' => $request->get('name'),
            'date' => $request->get('date'),
        ]);
        return $paginator;
    }

    // Exam schedule side

    public function exam_schedule(Request $request)
    {
        $this->data['getClass'] = $this->getClass();
        $this->data['getExamList'] = $this->getExamList();

        $result = array();
        if (!empty($request->get('exam_id')) && !empty($request->get('class_id'))) {

            $getSubject = $this->getSubject($request->get('class_id'));
            foreach ($getSubject as $value) {
                $datas = array();
                $datas['subject_id'] = $value->subjectData->id;
                $datas['class_id'] = $value->classData->id;
                $datas['subject_name'] = $value->subjectData->name;
                $datas['subject_type'] = $value->subjectData->type;

                $ExamSchedule = $this->NoDuplicateRecord(
                    $request->get('exam_id'),
                    $request->get('class_id'),
                    $value->subjectData->id,
                );

                if (!empty($ExamSchedule)) {
                    $datas['exam_date'] = $ExamSchedule->exam_date;
                    $datas['start_time'] = $ExamSchedule->start_time;
                    $datas['end_time'] = $ExamSchedule->end_time;
                    $datas['room_number'] = $ExamSchedule->room_number;
                    $datas['full_marks'] = $ExamSchedule->full_marks;
                    $datas['passing_marks'] = $ExamSchedule->passing_marks;
                } else {
                    $datas['exam_date'] = '';
                    $datas['start_time'] = '';
                    $datas['end_time'] = '';
                    $datas['room_number'] = '';
                    $datas['full_marks'] = '';
                    $datas['passing_marks'] = '';
                }

                $result[] = $datas;
            }
        }

        $this->data['getResult'] = $result;

        $this->data['header_title'] = 'Exam Schedule';
        return view('admin.examinations.exam_schedule')->with([
            'data' => $this->data,
        ]);
    }

    private function getExamList()
    {
        return ExamModel::orderBy('exam.name', 'asc')->get();
    }

    private function getClass()
    {
        $record = ClassModel::select('id', 'name')
            ->where('class.status', '=', '1')
            ->orderBy('class.name', 'asc')
            ->get();

        return $record;
    }

    private function getSubject($class_id)
    {
        return ClassSubjectModel::with(['classData', 'subjectData'])
            ->where('class_id', $class_id)
            ->where('status', 1)
            ->orderBy('id', 'desc')
            ->get();
    }

    public function exam_schedule_store(Request $request)
    {
        $this->deleteSameSchedule($request->exam_id, $request->class_id, $request->schedule);
        // dd($request->all());
        try {

            if (!empty($request->schedule)) {

                foreach ($request->schedule as $schedule) {
                    if (
                        !empty($schedule['subject_id']) && !empty($schedule['exam_date']) && !empty($schedule['start_time'])
                        && !empty($schedule['end_time']) && !empty($schedule['room_number']) && !empty($schedule['full_marks'])
                        && !empty($schedule['passing_marks'])
                    ) {
                        ExamScheduleModel::updateOrCreate(
                            [
                                'exam_id' => $request->exam_id,
                                'class_id' => $request->class_id,
                                'subject_id' => $schedule['subject_id'],
                            ],
                            [
                                'exam_date' => $schedule['exam_date'],
                                'start_time' => $schedule['start_time'],
                                'end_time' => $schedule['end_time'],
                                'room_number' => $schedule['room_number'],
                                'full_marks' => $schedule['full_marks'],
                                'passing_marks' => $schedule['passing_marks'],
                                'created_by' => Auth::user()->id,
                                'updated_by' => Auth::user()->id,
                            ]
                        );
                    }
                }
                return redirect()->back()->with('success', 'Exam Schedule successfully stored.');
            } else {
                return redirect()->back()->with('error', 'No schedule data provided.');
            }
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    private function NoDuplicateRecord($exam_id, $class_id, $subject_id)
    {
        return ExamScheduleModel::where('exam_id', '=', $exam_id)
            ->where('class_id', '=', $class_id)
            ->where('subject_id', '=', $subject_id)->first();
    }

    private function deleteSameSchedule($exam_id, $class_id, $schedule)
    {
        ExamScheduleModel::where('exam_id', '=', $exam_id)
            ->where('class_id', '=', $class_id);

        if (!empty($schedule['subject_id'])) {
            ExamScheduleModel::where('subject_id', '=', $schedule['subject_id']);
        }

        if (!empty($schedule['exam_date'])) {
            ExamScheduleModel::where('exam_date', '=', $schedule['exam_date']);
        }

        if (!empty($schedule['start_time'])) {
            ExamScheduleModel::where('start_time', '=', $schedule['start_time']);
        }

        if (!empty($schedule['end_time'])) {
            ExamScheduleModel::where('end_time', '=', $schedule['end_time']);
        }

        if (!empty($schedule['room_number'])) {
            ExamScheduleModel::where('room_number', '=', $schedule['room_number']);
        }

        if (!empty($schedule['full_marks'])) {
            ExamScheduleModel::where('full_marks', '=', $schedule['full_marks']);
        }

        if (!empty($schedule['passing_marks'])) {
            ExamScheduleModel::where('passing_marks', '=', $schedule['passing_marks']);
        }
    }

    //********* Exam Marks Register ********************************

    public function marks_register(Request $request)
    {
        $this->data['getClass'] = $this->getClass();
        $this->data['getExamList'] = $this->getExamList();

        if (!empty($request->get('exam_id')) && !empty($request->get('class_id'))) {
            $this->data['getExamSubject'] = $this->getExamSubject($request->get('exam_id'), $request->get('class_id'));
            $this->data['getStudentExamClass'] = $this->getStudentExamClass($request->get('class_id'));

            // Fetch existing marks from the database
            $existingMarks = MarksRegisterModel::whereIn('student_id', $this->data['getStudentExamClass']->pluck('id'))
                ->where('exam_id', $request->get('exam_id'))
                ->where('class_id', $request->get('class_id'))
                ->whereIn('subject_id', $this->data['getExamSubject']->pluck('subjectData.id'))
                ->get();

            // Organize existing marks into a convenient array for the view
            $existingMarksArray = [];
            foreach ($existingMarks as $mark) {
                $existingMarksArray[$mark->student_id][$mark->subject_id] = [
                    'exam_marks' => $mark->exam_marks,
                    'homework_marks' => $mark->homework_marks,
                ];
            }

            $this->data['existingMarks'] = $existingMarksArray;
        }

        $this->data['header_title'] = 'Marks Register';
        return view('admin.examinations.marks_register')->with([
            'data' => $this->data,
        ]);
    }

    public function marks_register_teacher(Request $request)
    {
        $this->data['getClass'] = $this->getClass();
        $this->data['getExamList'] = $this->getExamList();

        if (!empty($request->get('exam_id')) && !empty($request->get('class_id'))) {
            $this->data['getExamSubject'] = $this->getExamSubject($request->get('exam_id'), $request->get('class_id'));
            $this->data['getStudentExamClass'] = $this->getStudentExamClass($request->get('class_id'));

            // Fetch existing marks from the database
            $existingMarks = MarksRegisterModel::whereIn('student_id', $this->data['getStudentExamClass']->pluck('id'))
                ->where('exam_id', $request->get('exam_id'))
                ->where('class_id', $request->get('class_id'))
                ->whereIn('subject_id', $this->data['getExamSubject']->pluck('subjectData.id'))
                ->get();

            // Organize existing marks into a convenient array for the view
            $existingMarksArray = [];
            foreach ($existingMarks as $mark) {
                $existingMarksArray[$mark->student_id][$mark->subject_id] = [
                    'exam_marks' => $mark->exam_marks,
                    'homework_marks' => $mark->homework_marks,
                ];
            }

            $this->data['existingMarks'] = $existingMarksArray;
        }

        $this->data['header_title'] = 'Marks Register';
        return view('teacher.marks_register')->with([
            'data' => $this->data,
        ]);
    }


    private function getExamSubject($exam_id, $class_id)
    {
        return ExamScheduleModel::with('subjectData')
            ->where('exam_id', $exam_id)
            ->where('class_id', $class_id)
            ->get();
    }

    private function getStudentExamClass($class_id)
    {
        return User::where('users.user_type', '=', 3)
            ->with('parent', 'classData', 'subjectData')
            ->where('users.class_id', '=', $class_id)
            ->orderBy('users.id', 'desc')->get();
    }

    public function store_marks_register(Request $request)
    {
        try {
            if (!empty($request->marks)) {
                foreach ($request->marks as $mark) {
                    $exam_marks = !empty($mark['exam_marks']) ? $mark['exam_marks'] : 0;
                    $homework_marks = !empty($mark['homework_marks']) ? $mark['homework_marks'] : 0;

                    $existingMark = $this->NoDuplicateMark($request->student_id, $request->exam_id, $request->class_id, $mark['subject_id']);

                    if (!empty($existingMark)) {
                        // Update existing record
                        $existingMark->update([
                            'exam_marks' => $exam_marks,
                            'homework_marks' => $homework_marks,
                            'updated_by' => Auth::user()->id,
                        ]);
                    } else {
                        // Create new record
                        MarksRegisterModel::create([
                            'student_id' => $request->student_id,
                            'exam_id' => $request->exam_id,
                            'class_id' => $request->class_id,
                            'subject_id' => $mark['subject_id'],
                            'exam_marks' => $exam_marks,
                            'homework_marks' => $homework_marks,
                            'created_by' => Auth::user()->id,
                        ]);
                    }
                }
            }
            return redirect()->back()->with('success', 'Marks successfully stored.');
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
        // dd($request->all());
    }

    public function store_marks_register_teacher(Request $request)
    {
        try {
            if (!empty($request->marks)) {
                foreach ($request->marks as $mark) {
                    $exam_marks = !empty($mark['exam_marks']) ? $mark['exam_marks'] : 0;
                    $homework_marks = !empty($mark['homework_marks']) ? $mark['homework_marks'] : 0;

                    $existingMark = $this->NoDuplicateMark($request->student_id, $request->exam_id, $request->class_id, $mark['subject_id']);

                    if (!empty($existingMark)) {
                        // Update existing record
                        $existingMark->update([
                            'exam_marks' => $exam_marks,
                            'homework_marks' => $homework_marks,
                            'updated_by' => Auth::user()->id,
                        ]);
                    } else {
                        // Create new record
                        MarksRegisterModel::create([
                            'student_id' => $request->student_id,
                            'exam_id' => $request->exam_id,
                            'class_id' => $request->class_id,
                            'subject_id' => $mark['subject_id'],
                            'exam_marks' => $exam_marks,
                            'homework_marks' => $homework_marks,
                            'created_by' => Auth::user()->id,
                        ]);
                    }
                }
            }
            return redirect()->back()->with('success', 'Marks successfully stored.');
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
        // dd($request->all());
    }

    private function NoDuplicateMark($student_id, $exam_id, $class_id, $subject_id)
    {
        return MarksRegisterModel::with('student', 'exam', 'classData', 'subjectData')
            ->where('student_id', $student_id)
            ->where('exam_id', $exam_id)
            ->where('class_id', $class_id)
            ->where('subject_id', $subject_id)->first();
    }

    // ********* Student side *********
    public function student_exam_timetable()
    {
        $class_id = Auth::user()->class_id;
        $exams = ExamScheduleModel::where('class_id', $class_id)
            ->with(['exam', 'classData', 'subjectData'])
            ->orderBy('id', 'asc')
            ->get();

        $this->data['getRecord'] = $exams;
        // dd($data['getRecord']);

        $this->data['header_title'] = 'My Exam Timetable';
        return view('student.my_exam_timetable')->with([
            'data' => $this->data,
        ]);
    }

    //*********** Teacher side ***********
    public function teacher_exam_timetable()
    {
        $getClass = $this->getMyClassSubject(Auth::user()->id);
        $classIds = $getClass->pluck('class_id')->toArray();

        $exam = ExamScheduleModel::whereIn('class_id', $classIds)
            ->with(['exam', 'classData', 'subjectData'])
            ->orderBy('id', 'asc')
            ->get();

        $this->data['getTimetable'] = $exam;

        $this->data['header_title'] = 'My Exam Timetable';
        return view('teacher.my_exam_timetable')->with([
            'data' => $this->data,
        ]);
    }

    private function getMyClassSubject($teacher_id)
    {
        return AssignClassTeacherModel::where('assign_class_teacher.status', '=', 1)
            ->where('assign_class_teacher.teacher_id', '=', $teacher_id)
            ->with('classData', 'subjectData')->get();
    }

    //********** Parent Side ***********
    public function parent_exam_timetable($student_id)
    {
        $getStudent = User::findOrFail($student_id);
        $class_id = $getStudent->class_id;
        $exams = ExamScheduleModel::where('class_id', $class_id)
            ->with(['exam', 'classData', 'subjectData'])
            ->orderBy('id', 'asc')
            ->get();

        $this->data['getMyStudentTimeTable'] = $exams;
        $this->data['getStudent'] = $getStudent;

        $this->data['header_title'] = 'My Student Exam Timetable';
        return view('parent.my_exam_timetable')->with([
            'data' => $this->data,
        ]);
    }
}
