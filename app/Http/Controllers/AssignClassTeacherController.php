<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use App\Helper\FormHelper;
use App\Models\ClassModel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\AssignClassTeacherModel;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class AssignClassTeacherController extends Controller
{
    public $pagination = '';
    public $data = [];
    public function __construct()
    {
        $config = FormHelper::getConfig();
        $this->pagination = $config['paginate'];
        $this->data = [
            'header_title' => 'Assigned Class Teacher List',
            'getRecord' => [],
            'getClass' => [],
            'getTeacher' => [],

        ];
    }

    public function index(Request $request)
    {
        $this->data['getRecord'] = $this->getRecord($request);
        return view('admin.assign_class_teacher.list')->with([
            'data' => $this->data,
        ]);
    }

    public function create()
    {
        $this->data['getClass'] = $this->getClass();
        $this->data['getTeacher'] = $this->getTeacher();
        $this->data['header_title'] = 'Assign A New Class Teacher';
        return view('admin.assign_class_teacher.add')->with([
            'data' => $this->data,
        ]);
    }

    public function store(Request $request)
    {
        if (!empty($request->teacher_id)) {
            foreach ($request->teacher_id as $teacher_id) {
                $getAlreadyFirst = $this->getAlreadyFirst($request->class_id, $teacher_id);
                if (!empty($getAlreadyFirst)) {
                    $getAlreadyFirst->status = $request->status;
                    $getAlreadyFirst->save();
                } else {
                    $assign = AssignClassTeacherModel::create([
                        'class_id' => $request->class_id,
                        'teacher_id' => $teacher_id,
                        'status' => $request->status,
                        'created_by' => Auth::user()->id,
                    ]);

                    // $teacher = User::findOrFail($teacher_id);
                    // $teacher->class_id = $request->class_id;
                    // $teacher->save();
                }
            }

            return redirect('admin/assign_class_teacher/list')->with('success', 'Teacher successfully assigned to class');
        } else {
            return redirect()->back()->with('error', 'Please Select Subject');
        }
    }

    public function edit($id)
    {
        $record = AssignClassTeacherModel::findOrFail($id);
        if (!empty($record)) {
            $this->data['getRecord'] = $record;
            $this->data['getClass'] = $this->getClass();
            $this->data['getTeacher'] = $this->getTeacher();

            // Retrieve assigned subject IDs for the selected class
            $this->data['assignTeacherIDs'] = $this->getAssignTeacherID($record->class_id);

            $this->data['header_title'] = 'Edit Assigned Class Teacher';
            return view('admin.assign_class_teacher.edit')->with([
                'data' => $this->data,
            ]);
        }
    }

    public function update(Request $request)
    {
        if (!empty($request->teacher_id)) {
            foreach ($request->teacher_id as $teacher_id) {
                $getAlreadyFirst = $this->getAlreadyFirst($request->class_id, $teacher_id);
                if (!empty($getAlreadyFirst)) {
                    $getAlreadyFirst->status = $request->status;
                    $getAlreadyFirst->save();
                } else {
                    $assign = new AssignClassTeacherModel();
                    $assign->class_id = $request->class_id;
                    $assign->teacher_id = $teacher_id;
                    $assign->status = $request->status;
                    $assign->created_by = Auth::user()->id;
                    $assign->save();
                }
            }
        }
        return redirect('admin/assign_class_teacher/list')->with('success', 'Subject successfully assigned to class');
    }

    public function destroy($id)
    {
        try {
            $record = AssignClassTeacherModel::findOrFail($id);
            $record->delete();

            return redirect('/admin/assign_class_teacher/list')->with('success', 'Assigned Class Teacher is soft Deleted Successfully');
        } catch (ModelNotFoundException $exception) {
            return redirect('/admin/assign_class_teacher/list')->with('error', $exception->getMessage());
        } catch (Exception $e) {
            return redirect('/admin/assign_class_teacher/list')->with('error', $e->getMessage());
        }
    }

    public function deletedList()
    {
        try {
            $softDeletedRecords = AssignClassTeacherModel::onlyTrashed()->get();

            return view('admin.assign_class_teacher.deleted_list', compact('softDeletedRecords'));
        } catch (ModelNotFoundException $exception) {
            return redirect('/admin/assign_class_teacher/list')->with('error', $exception->getMessage());
        } catch (Exception $e) {
            return redirect('/admin/assign_class_teacher/list')->with('error', $e->getMessage());
        }
    }

    public function restore($id)
    {
        try {
            AssignClassTeacherModel::withTrashed()->where('id', $id)->restore();

            return redirect('/admin/assign_class_teacher/list')->with('success', 'Assigned Class Teacher is restored successfully');
        } catch (ModelNotFoundException $exception) {
            return redirect('/admin/assign_class_teacher/list')->with('error', $exception->getMessage());
        } catch (Exception $e) {
            return redirect('/admin/assign_class_teacher/list')->with('error', $e->getMessage());
        }
    }

    public function forceDelete($id)
    {
        try {
            AssignClassTeacherModel::withTrashed()->where('id', $id)->forceDelete();

            return redirect('/admin/assign_class_teacher/list')->with('success', 'Assigned Class Teacher is permanently deleted');
        } catch (ModelNotFoundException $exception) {
            return redirect('/admin/assign_class_teacher/list')->with('error', $exception->getMessage());
        } catch (Exception $e) {
            return redirect('/admin/assign_class_teacher/list')->with('error', $e->getMessage());
        }
    }

    private function getRecord(Request $request)
    {
        $query = AssignClassTeacherModel::with(['classData', 'teacher', 'createdBy', 'updatedBy']);

        if (!empty($request->get('class_name'))) {
            $query->whereHas('classData', function ($subQuery) use ($request) {
                $subQuery->where('name', 'like', '%' . $request->get('class_name') . '%');
            });
        }

        if (!empty($request->get('teacher_name'))) {
            $query->whereHas('parent', function ($subQuery) use ($request) {
                $subQuery->where('name', 'like', '%' . $request->get('teacher_name') . '%');
            });
        }

        if (!empty($request->get('date'))) {
            $query->whereDate('assign_class_teacher.created_at', '=', $request->get('date'));
        }

        $paginator = $query->orderBy('assign_class_teacher.id', 'desc')->paginate($this->pagination);

        $paginator->appends([
            'class_name' => $request->get('class_name'),
            'teacher_name' => $request->get('teacher_name'),
            'date' => $request->get('date'),
        ]);

        return $paginator;
    }

    private function getClass()
    {
        $record = ClassModel::select('id', 'name')
            ->where('class.status', '=', '1')
            ->orderBy('class.name', 'asc')
            ->get();

        return $record;
    }

    private function getTeacher()
    {
        $record = User::select('id', 'name')
            ->where('users.user_type', '=', 2)
            ->orderBy('users.name', 'asc')
            ->get();

        return $record;
    }

    private function getAlreadyFirst($class_id, $teacher_id)
    {
        return AssignClassTeacherModel::where('class_id', '=', $class_id)->where('teacher_id', '=', $teacher_id)->first();
    }

    private function getAssignTeacherID($class_id)
    {
        return AssignClassTeacherModel::where('class_id', '=', $class_id)->pluck('teacher_id')->toArray();
    }

    //**********  Teacher Side *********

    public function myClassSubject()
    {
        $this->data['getClassSubject'] = $this->getMyClassSubject(Auth::user()->id);
        $this->data['header_title'] = 'My Class & Subjects';
        return view('teacher.my_class_subject')->with([
            'data' => $this->data,
        ]);
    }

    private function getMyClassSubject($teacher_id)
    {
        return AssignClassTeacherModel::with(['assignedClasses.subjects'])
            ->where('teacher_id', $teacher_id)
            ->where('status', 1)
            ->get();
    }
}
