<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\ClassModel;
use App\Models\SubjectModel;
use Illuminate\Http\Request;
use App\Models\ClassSubjectModel;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ClassSubjectController extends Controller
{
    public $pagination = 6;
    public $data = [];
    public function __construct()
    {
        $this->data = [
            'header_title' => 'Assigned Subjects List',
            'getRecord' => [],
            'getClass' => [],
            'getSubject' => [],

        ];
    }

    public function index(Request $request)
    {
        $this->data['getRecord'] = $this->getRecord($request);
        return view('admin.assign_subject.list')->with([
            'data' => $this->data,
        ]);
    }

    public function create()
    {
        $this->data['getClass'] = $this->getClass();
        $this->data['getSubject'] = $this->getSubject();
        $this->data['header_title'] = 'Add Assigned Subject';
        return view('admin.assign_subject.add')->with([
            'data' => $this->data,
        ]);
    }

    public function store(Request $request)
    {
        if (!empty($request->subject_id)) {
            foreach ($request->subject_id as $subject_id) {
                $getAlreadyFirst = $this->getAlreadyFirst($request->class_id, $subject_id);
                if (!empty($getAlreadyFirst)) {
                    $getAlreadyFirst->status = $request->status;
                    $getAlreadyFirst->save();
                } else {
                    $assign = ClassSubjectModel::create([
                        'class_id' => $request->class_id,
                        'subject_id' => $subject_id,
                        'status' => $request->status,
                        'created_by' => Auth::user()->id,
                    ]);
                }
            }

            return redirect('admin/assign_subject/list')->with('success', 'Subject successfully assigned to class');
        } else {
            return redirect()->back()->with('error', 'Please Select Subject');
        }
    }

    public function edit($id)
    {
        $record = ClassSubjectModel::findOrFail($id);
        if (!empty($record)) {
            $this->data['getRecord'] = $record;
            $this->data['getClass'] = $this->getClass();
            $this->data['getSubject'] = $this->getSubject();

            // Retrieve assigned subject IDs for the selected class
            $this->data['assignSubjectIDs'] = $this->getAssignSubjectID($record->class_id);

            $this->data['header_title'] = 'Edit Assigned Subject';
            return view('admin.assign_subject.edit')->with([
                'data' => $this->data,
            ]);
        }
    }



    public function update(Request $request)
    {
        if (!empty($request->subject_id)) {
            foreach ($request->subject_id as $subject_id) {
                $getAlreadyFirst = $this->getAlreadyFirst($request->class_id, $subject_id);
                if (!empty($getAlreadyFirst)) {
                    $getAlreadyFirst->status = $request->status;
                    $getAlreadyFirst->save();
                } else {
                    $assign = new ClassSubjectModel();
                    $assign->class_id = $request->class_id;
                    $assign->subject_id = $subject_id;
                    $assign->status = $request->status;
                    $assign->created_by = Auth::user()->id;
                    $assign->save();
                }
            }
        }
        return redirect('admin/assign_subject/list')->with('success', 'Subject successfully assigned to class');
    }

    public function destroy($id)
    {
        try {
            $subject = ClassSubjectModel::findOrFail($id);
            $subject->delete();

            return redirect('/admin/assign_subject/list')->with('success', 'Assigned Subject is soft Deleted Successfully');
        }catch (ModelNotFoundException $exception) {
            return redirect('/admin/assign_subject/list')->with('error', $exception->getMessage());
        }catch(Exception $e) {
            return redirect('/admin/assign_subject/list')->with('error', $e->getMessage());
        }
    }

    public function deletedList()
    {
        try {
            $softDeletedRecords = ClassSubjectModel::onlyTrashed()->get();

            return view('admin.assign_subject.deleted_list', compact('softDeletedRecords'));
        }catch (ModelNotFoundException $exception) {
            return redirect('/admin/assign_subject/list')->with('error', $exception->getMessage());
        }catch(Exception $e) {
            return redirect('/admin/assign_subject/list')->with('error', $e->getMessage());
        }
    }

    public function restore($id)
    {
        try {
            ClassSubjectModel::withTrashed()->where('id', $id)->restore();

            return redirect('/admin/assign_subject/list')->with('success', 'Assigned Subject is restored successfully');
        } catch (ModelNotFoundException $exception) {
            return redirect('/admin/assign_subject/list')->with('error', $exception->getMessage());
        }catch(Exception $e) {
            return redirect('/admin/assign_subject/list')->with('error', $e->getMessage());
        }
    }

    public function forceDelete($id)
    {
        try {
            ClassSubjectModel::withTrashed()->where('id', $id)->forceDelete();

            return redirect('/admin/assign_subject/list')->with('success', 'Assigned Subject is permanently deleted');
        }catch (ModelNotFoundException $exception) {
            return redirect('/admin/assign_subject/list')->with('error', $exception->getMessage());
        }catch(Exception $e) {
            return redirect('/admin/assign_subject/list')->with('error', $e->getMessage());
        }
    }

    private function getRecord(Request $request)
    {
        $query = ClassSubjectModel::with(['classData', 'subjectData', 'createdBy', 'updatedBy'])
            ->whereNull('class_subject.deleted_at');

        if (!empty($request->get('class_name'))) {
            $query->whereHas('classData', function ($subQuery) use ($request) {
                $subQuery->where('name', 'like', '%' . $request->get('class_name') . '%');
            });
        }

        if (!empty($request->get('subject_name'))) {
            $query->whereHas('subjectData', function ($subQuery) use ($request) {
                $subQuery->where('name', 'like', '%' . $request->get('subject_name') . '%');
            });
        }

        if (!empty($request->get('date'))) {
            $query->whereDate('class_subject.created_at', '=', $request->get('date'));
        }

        $paginator = $query->orderBy('class_subject.id', 'desc')->paginate($this->pagination);

        $paginator->appends([
            'class_name' => $request->get('class_name'),
            'subject_name' => $request->get('subject_name'),
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

    private function getSubject()
    {
        $record = SubjectModel::select('id', 'name')
            ->where('subject.status', '=', '1')
            ->orderBy('subject.name', 'asc')
            ->get();

        return $record;
    }

    private function getAlreadyFirst($class_id, $subject_id)
    {
        return ClassSubjectModel::where('class_id', '=', $class_id)->where('subject_id', '=', $subject_id)->first();
    }

    private function getAssignSubjectID($class_id)
    {
        return ClassSubjectModel::where('class_id', '=', $class_id)->whereNull('deleted_at')->pluck('subject_id')->toArray();
    }
}
