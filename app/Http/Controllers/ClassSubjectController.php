<?php

namespace App\Http\Controllers;

use App\Models\ClassModel;
use App\Models\SubjectModel;
use Illuminate\Http\Request;
use App\Models\ClassSubjectModel;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ClassSubjectController extends Controller
{
    public function list(Request $request)
    {
        $data['getRecord'] = ClassSubjectModel::getRecord($request);
        $data['header_title'] = 'Assign Subject List';
        return view('admin.assign_subject.list', $data);
    }

    public function add(Request $request)
    {
        $data['getClass'] = ClassModel::getClass();
        $data['getSubject'] = SubjectModel::getSubject();
        $data['header_title'] = 'Add Assigned Subject';
        return view('admin.assign_subject.add', $data);
    }

    public function insert(Request $request)
    {
        if (!empty($request->subject_id)) {
            foreach ($request->subject_id as $subject_id) {
                $getAlreadyFirst = ClassSubjectModel::getAlreadyFirst($request->class_id, $subject_id);
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

            return redirect('admin/assign_subject/list')->with('success', 'Subject successfully assigned to class');
        } else {
            return redirect()->back()->with('error', 'Please Select Subject');
        }
    }

    public function edit($id)
    {
        $getRecord = ClassSubjectModel::getSingle($id);
        if (!empty($getRecord)) {
            $data['getRecord'] = $getRecord;
            $data['getAssignSubjectID'] = ClassSubjectModel::getAssignSubjectID($getRecord->class_id);
            $data['getClass'] = ClassModel::getClass();
            $data['getSubject'] = SubjectModel::getSubject();
            $data['header_title'] = 'Edit Assigned Subject';
            return view('admin.assign_subject.edit', $data);
        } else {
            abort(404);
        }
    }

    public function update(Request $request)
    {
        ClassSubjectModel::deleteSubject($request->class_id);

        if (!empty($request->subject_id)) {
            foreach ($request->subject_id as $subject_id) {
                $getAlreadyFirst = ClassSubjectModel::getAlreadyFirst($request->class_id, $subject_id);
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

    public function delete($id)
    {
        $data = ClassSubjectModel::getSingle($id);
        $data->is_delete = 1;
        $data->save();

        return redirect()->back()->with('success', 'Subject assignment successfully deleted');
    }

    public function edit_single($id)
    {
        $getRecord = ClassSubjectModel::getSingle($id);
        if (!empty($getRecord)) {
            $data['getRecord'] = $getRecord;
            $data['getClass'] = ClassModel::getClass();
            $data['getSubject'] = SubjectModel::getSubject();
            $data['header_title'] = 'Edit Assigned Subject';
            return view('admin.assign_subject.edit_single', $data);
        } else {
            abort(404);
        }
    }

    public function update_single($id, Request $request)
    {

        $getAlreadyFirst = ClassSubjectModel::getAlreadyFirst($request->class_id, $request->subject_id);
        if (!empty($getAlreadyFirst)) {
            $getAlreadyFirst->status = $request->status;
            $getAlreadyFirst->save();

            return redirect('admin/assign_subject/list')->with('success', 'Status successfully updated');
        } else {
            $assign = ClassSubjectModel::getSingle($id);
            $assign->class_id = $request->class_id;
            $assign->subject_id = $request->subject_id;
            $assign->status = $request->status;
            $assign->save();

            return redirect('admin/assign_subject/list')->with('success', 'Subject successfully assigned to class');
        }

    }
}
