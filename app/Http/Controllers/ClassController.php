<?php

namespace App\Http\Controllers;

use App\Models\ClassModel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ClassController extends Controller
{
    public function list(Request $request)
    {
        $data['getRecord'] = ClassModel::getRecord($request);
        $data['header_title'] = 'Class List';
        return view('admin.class.list', $data);
    }

    public function add()
    {
        $data['header_title'] = 'Add New Class';
        return view('admin.class.add', $data);
    }

    public function insert(Request $request)
    {
        $class = new ClassModel;
        $class->name = $request->name;
        $class->status = $request->status;
        $class->created_by = Auth::user()->id;
        $class->save();

        return redirect('/admin/class/list')->with('success', 'New Class is Added Successfully');
    }

    public function edit($id)
    {
        $data['getRecord'] = ClassModel::getSingle($id);
        if (!empty($data['getRecord'])) {
            $data['header_title'] = 'Edit Class';
            return view('admin.class.edit', $data);
        } else {
            abort(404);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $class = ClassModel::getSingle($id);

            if (!$class) {
                throw new \Exception("Class not found with ID: $id");
            }

            $class->name = $request->name;
            $class->status = $request->status;
            $class->save();

            return redirect('/admin/class/list')->with('success', 'Class is Updated Successfully');
        } catch (\Exception $e) {
            return redirect('/admin/class/list')->with('error', 'Error updating class: ' . $e->getMessage());
        }
    }


    public function delete($id)
    {
        try{
            $class = ClassModel::findOrFail($id);
            $class->delete();

            return redirect('/admin/class/list')->with('success', 'Class is soft Deleted Successfully');
        } catch (ModelNotFoundException $e) {
            return redirect('/admin/class/list')->with('error', 'Class data not found');
        }
    }

    public function deletedList()
  {
      try {
          $softDeletedRecords = ClassModel::onlyTrashed()->whereNotNull('class.deleted_at')->get();

          return view('admin.class.deleted_list', compact('softDeletedRecords'));
      } catch (\Exception $e) {
          return redirect('/admin/class/list')->with('error', 'Error retrieving soft-deleted records');
      }
  }

  public function restore($id)
    {
        try {
            ClassModel::withTrashed()->where('id', $id)->whereNotNull('class.deleted_at')->restore();

            return redirect('/admin/class/list')->with('success', 'Class data is restored successfully');
        } catch (ModelNotFoundException $exception) {
            return redirect('/admin/class/list')->with('error', 'Class data not found');
        }
    }

    public function forceDelete($id)
    {
        try {
            ClassModel::withTrashed()->where('id', $id)->whereNotNull('class.deleted_at')->forceDelete();

            return redirect('/admin/class/list')->with('success', 'Class data is permanently deleted');
        } catch (ModelNotFoundException $exception) {
            return redirect('/admin/class/list')->with('error', 'Class data not found');
        }
    }
}
