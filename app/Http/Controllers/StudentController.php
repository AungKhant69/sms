<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\ClassModel;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class StudentController extends Controller
{
    public function list(Request $request)
    {
        $data['getRecord'] = User::getStudent($request);
        $data['header_title'] = 'Student List';
        return view('admin.student.list', $data);
    }

    public function add()
    {
        $data['getClass'] = ClassModel::getClass();
        $data['header_title'] = 'Add New Student';
        return view('admin.student.add', $data);
    }

    public function insert(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'roll_number' => 'required|integer|unique:users',
            'gender' => ['required', Rule::in(['male', 'female', 'other'])],
            'religion' => 'required|string|max:50',
            'admission_date' => 'required|date',
            'blood_group' => 'required|string|max:10',
            'weight' => 'required|numeric',
            'email' => 'required|email|unique:users',
            'admission_number' => 'required|integer|unique:users',
            'class' => 'required|integer',
            'date_of_birth' => 'required|date',
            'mobile_number' => 'required|numeric',
            'height' => 'required|numeric',
            'status' => ['required', Rule::in(['active', 'inactive'])],
            'password' => 'required|string|min:6'
        ]);

        $student = new User;
        $student->name = trim($request->name);
        $student->admission_number = trim($request->admission_number);
        $student->roll_number = trim($request->roll_number);
        $student->class_id = trim($request->class_id);
        $student->gender = trim($request->gender);

        if(!empty($request->date_of_birth)){
        $student->date_of_birth = trim($request->date_of_birth);
        }

        $student->religion = trim($request->religion);
        $student->mobile_number = trim($request->mobile_number);

        if(!empty($request->admission_date)){
        $student->admission_date = trim($request->admission_date);
        }

        if(!empty($request->file('profile_pic'))){
            $extension = $request->file('profile_pic')->getClientOriginalExtension();
            $file = $request->file('profile_pic');
            $randomStr = date('Ymshis') . Str::random(20);
            $filename = strtolower($randomStr) . '.' . $extension;
            $file->move('uploads/profile', $filename);

            $student->profile_pic = $filename;
        }

        $student->blood_group = trim($request->blood_group);
        $student->height = trim($request->height);
        $student->weight = trim($request->weight);
        $student->status = trim($request->status);
        $student->email = trim($request->email);
        $student->password = Hash::make($request->password);
        $student->user_type = 3;
        $student->save();

        return redirect('/admin/student/list')->with('success', 'New Student is Added Successfully');

    }
}
