<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use App\Helper\FormHelper;
use App\Models\SubjectModel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreSubjectRequest;
use App\Http\Requests\UpdateSubjectRequest;
use App\Models\ClassSubjectModel;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class SubjectController extends Controller
{
    public $pagination = '';
    public $data = [];
    public function __construct()
    {
        $config = FormHelper::getConfig();
        $this->pagination = $config['paginate'];
        $this->data = [
            'header_title' => 'Subject List',
            'getRecord' => [],
        ];
    }

    public function index(Request $request)
    {
        $this->data['getRecord'] = $this->getRecord($request);
        return view('admin.subject.list')->with([
            'data' => $this->data,
        ]);
    }

    public function create()
    {
        $this->data['header_title'] = 'Add Subject';
        return view('admin.subject.add')->with([
            'data' => $this->data,
        ]);
    }

    public function store(StoreSubjectRequest $request)
    {
        $subject = SubjectModel::create([
            'name' => $request->name,
            'type' => $request->type,
            'status' => $request->status,
            'created_by' => auth()->user()->id,
        ]);

        return redirect('/admin/subject/list')->with('success', 'Subject is Added Successfully');
    }

    public function edit($id)
    {
        $this->data['getRecord'] = SubjectModel::findOrFail($id);
        if (!empty($this->data['getRecord'])) {
            $this->data['header_title'] = 'Edit Subject';
            return view('admin.subject.edit')->with([
                'data' => $this->data,
            ]);
        }
    }

    public function update(UpdateSubjectRequest $request, $id)
    {
        try {
            $subject = SubjectModel::findOrFail($id);

            $subject->update([
                'name' => $request->name,
                'type' => $request->type,
                'status' => $request->status,
                'updated_by' => auth()->user()->id,
            ]);

            return redirect('/admin/subject/list')->with('success', 'Subject is Updated Successfully');
        } catch (ModelNotFoundException $exception) {
            return redirect('/admin/subject/list')->with('error', $exception->getMessage());
        } catch (Exception $e) {
            return redirect('/admin/subject/list')->with('error', $e->getMessage());
        }
    }


    public function destroy($id)
    {
        try {
            $subject = SubjectModel::findOrFail($id);
            $subject->delete();

            return redirect('/admin/subject/list')->with('success', 'Subject is soft Deleted Successfully');
        } catch (ModelNotFoundException $exception) {
            return redirect('/admin/subject/list')->with('error', $exception->getMessage());
        } catch (Exception $e) {
            return redirect('/admin/subject/list')->with('error', $e->getMessage());
        }
    }

    public function deletedList()
    {
        try {
            $softDeletedRecords = SubjectModel::onlyTrashed()->whereNotNull('subject.deleted_at')->get();

            return view('admin.subject.deleted_list', compact('softDeletedRecords'));
        } catch (ModelNotFoundException $exception) {
            return redirect('/admin/subject/list')->with('error', $exception->getMessage());
        } catch (Exception $e) {
            return redirect('/admin/subject/list')->with('error', $e->getMessage());
        }
    }

    public function restore($id)
    {
        try {
            SubjectModel::withTrashed()->where('id', $id)->restore();

            return redirect('/admin/subject/list')->with('success', 'Subject data is restored successfully');
        } catch (ModelNotFoundException $exception) {
            return redirect('/admin/subject/list')->with('error', $exception->getMessage());
        } catch (Exception $e) {
            return redirect('/admin/subject/list')->with('error', $e->getMessage());
        }
    }

    public function forceDelete($id)
    {
        try {
            SubjectModel::withTrashed()->where('id', $id)->forceDelete();

            return redirect('/admin/subject/list')->with('success', 'Subject data is permanently deleted');
        } catch (ModelNotFoundException $exception) {
            return redirect('/admin/subject/list')->with('error', $exception->getMessage());
        } catch (Exception $e) {
            return redirect('/admin/subject/list')->with('error', $e->getMessage());
        }
    }

    private function getRecord(Request $request) //search bar for records
    {
        $record = SubjectModel::with('createdBy', 'updatedBy');

        if (!empty($request->get('name'))) {
            $record = $record->where('subject.name', 'like', '%' . $request->get('name') . '%');
        }

        if (!empty($request->get('type'))) {
            $record = $record->where('subject.type', 'like', '%' . $request->get('type') . '%');
        }

        if (!empty($request->get('date'))) {
            $record = $record->whereDate('subject.created_at', '=', $request->get('date'));
        }

        $paginator = $record->orderBy('subject.id', 'desc')->paginate($this->pagination);
        $paginator->appends([
            'name' => $request->get('name'),
            'type' => $request->get('type'),
            'date' => $request->get('date'),
        ]);

        return $paginator;
    }

    //************ Student side **********

    public function studentSubject()
    {
        $this->data['getSubject'] = $this->mySubject(Auth::user()->class_id);
        $this->data['header_title'] = 'Subject List';
        // dd($this->data['getSubject']);
        return view('student.my_subject')->with([
            'data' => $this->data,
        ]);
    }

    //************ Parent side **********

    public function ParentStudentSubject($student_id)
    {
        $user = User::findOrFail($student_id);
        $this->data['getUser'] = $user;
        $this->data['getRecord'] = $this->mySubject($user->class_id);
        $this->data['header_title'] = 'Student Subject';
        return view('parent.my_student_subject')->with([
            'data' => $this->data,
        ]);
    }

    private function mySubject($class_id)
    {
        return ClassSubjectModel::with(['classData', 'subjectData'])
            ->where('class_id', $class_id)
            ->where('status', 1)
            ->orderBy('id', 'desc')
            ->get();
    }
}
