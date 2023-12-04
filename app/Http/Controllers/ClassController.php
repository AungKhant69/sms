<?php

namespace App\Http\Controllers;

use App\Models\ClassModel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ClassController extends Controller
{
    public function list(Request $request){
        $data['getRecord'] = ClassModel::getRecord($request);
        $data['header_title'] = 'Class List';
        return view('admin.class.list', $data);
    }

    public function add(){
        $data['header_title'] = 'Add New Class';
        return view('admin.class.add', $data);
    }

    public function insert(Request $request){
        $class = new ClassModel;
        $class->name = $request->name;
        $class->status = $request->status;
        $class->created_by = Auth::user()->id;
        $class->save();

        return redirect('/admin/class/list')->with('success', 'New Class is Added Successfully');
    }

    public function edit($id){
        $data['getRecord'] = ClassModel::getSingle($id);
        if(!empty($data['getRecord'])){
            $data['header_title'] = 'Edit Class';
            return view('admin.class.edit', $data);
        }else{
            abort(404);
        }

    }

    public function update(Request $request, $id){
        $class = ClassModel::getSingle($id);
        $class->name = $request->name;
        $class->status = $request->status;
        // $class->updated_by = Auth::user()->id;
        $class->save();

        return redirect('/admin/class/list')->with('success', 'Class is Updated Successfully');
    }

    public function delete($id){
        $class = ClassModel::getSingle($id);
        $class->is_delete = 1;
        $class->save();

        return redirect()->with('success', 'Class is Deleted Successfully');
    }
}
