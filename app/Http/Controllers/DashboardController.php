<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\ExamModel;
use App\Helper\FormHelper;
use App\Models\ClassModel;
use App\Models\SubjectModel;
use Illuminate\Http\Request;
use App\Models\HomeworkModel;
use App\Models\ClassSubjectModel;
use App\Models\ExamScheduleModel;
use App\Models\AddStudentFeesModel;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\AssignClassTeacherModel;

class DashboardController extends Controller
{
    public $pagination = '';
    public $data = [];
    public function __construct()
    {
        $config = FormHelper::getConfig();
        $this->pagination = $config['paginate'];
        $this->data = [
            'header_title' => 'Dashboard',
            'getRecord' => [],
        ];
    }

    public function dashboard()
    {
        if (Auth::user()->user_type == 1)
        {
            $this->data['getTotalPayment'] = $this->getTotalPayment();
            $this->data['totalExam'] = $this->totalExam();
            $this->data['totalClass'] = $this->totalClass();
            $this->data['totalSubject'] = $this->totalSubject();
            $this->data['totalAdmin'] = $this->getTotalUser(1);
            $this->data['totalTeacher'] = $this->getTotalUser(2);
            $this->data['totalStudent'] = $this->getTotalUser(3);
            $this->data['totalParent'] = $this->getTotalUser(3);

            return view('admin/dashboard')->with([
                'data' => $this->data,
            ]);
        }
         else if (Auth::user()->user_type == 2)
        {
            $this->data['myStudentCount'] = $this->getMyStudentCount(Auth::user()->id);
            $this->data['getMyClassCount'] = $this->getMyClassCount(Auth::user()->id);
            $this->data['getMySubjectCount'] = $this->getMySubjectCount(Auth::user()->id);

            return view('teacher/dashboard')->with([
                'data' => $this->data,
            ]);
        }
         else if (Auth::user()->user_type == 3)
        {
            $this->data['studentSubjectCount'] = $this->studentSubjectCount(Auth::user()->class_id);
            $this->data['myExamCount'] = $this->myExamCount();
            $this->data['getHomeworkCount'] = $this->getHomeworkCount(Auth::user()->class_id);

            return view('student/dashboard')->with([
                'data' => $this->data,
            ]);
        }
         else if (Auth::user()->user_type == 4)
        {
            $this->data['myChildCount'] = $this->myChildCount(Auth::user()->id);
            $this->data['getMyPaidAmount'] = $this->getMyPaidAmount(Auth::user()->id);

            return view('parent/dashboard')->with([
                'data' => $this->data,
            ]);
        }
    }

    private function getTotalUser($user_type)
    {
        return User::where('user_type', $user_type)->count();
    }

    private function getTotalPayment()
    {
        return AddStudentFeesModel::sum('paid_amount');
    }

    private function getMyPaidAmount($parent_id)
    {
        return AddStudentFeesModel::where('created_by', $parent_id)
                                    ->sum('paid_amount');
    }

    private function totalExam()
    {
        return ExamModel::where('exam.status', '=', 1)->count();
    }

    private function totalClass()
    {
        return ClassModel::where('class.status', '=', 1)->count();
    }

    private function totalSubject()
    {
        return SubjectModel::where('subject.status', '=', 1)->count();
    }

    private function getMyStudentCount($teacher_id)
    {
        $students = User::where('users.user_type', '=', 3)
            ->whereHas('classTeachers', function ($query) use ($teacher_id) {
                $query->where('assign_class_teacher.teacher_id', '=', $teacher_id)
                    ->where('assign_class_teacher.status', '=', 1);
            })
            ->with('classData', 'parent')
            ->orderBy('users.id', 'asc')
            ->count();

        return $students;
    }

    private function getMyClassCount($teacher_id)
    {
        return AssignClassTeacherModel::where('teacher_id', $teacher_id)
            ->where('status', 1)
            ->distinct('class_id')
            ->count('class_id');
    }

    private function getMySubjectCount($teacher_id)
    {
        $classIds = AssignClassTeacherModel::where('teacher_id', $teacher_id)
            ->where('status', 1)
            ->distinct('class_id')
            ->pluck('class_id');

        $subjectCount = ClassModel::whereIn('id', $classIds)
            ->with('subjects')
            ->get()
            ->pluck('subjects.*')
            ->flatten()
            ->count();

        return $subjectCount;
    }

    private function studentSubjectCount($class_id)
    {
        return ClassSubjectModel::with(['classData', 'subjectData'])
            ->where('class_id', $class_id)
            ->where('status', 1)
            ->count('subject_id');
    }

    private function myExamCount()
    {
        $class_id = Auth::user()->class_id;
        $exams = ExamScheduleModel::where('class_id', $class_id)
            ->with(['exam', 'classData', 'subjectData'])
            ->count();
        return $exams;
    }

    private function getHomeworkCount($class_id)
    {
        $subjectIds = ClassSubjectModel::where('class_id', $class_id)->pluck('subject_id');

        $homeworks = HomeworkModel::whereIn('subject_id', $subjectIds)
            ->with('subjectData', 'classData', 'student')
            ->count();

        return $homeworks;
    }

    private function myChildCount($parent_id)
    {
        return User::where('users.user_type', '=', 3)
            ->where('users.parent_id', '=', $parent_id)
            ->with('parent')->count();

    }

}
