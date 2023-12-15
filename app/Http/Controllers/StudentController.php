<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use App\Helper\FormHelper;
use App\Models\ClassModel;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\StoreStudentRequest;
use App\Http\Requests\UpdateStudentRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class StudentController extends Controller
{
    public $pagination = 5;
    public $data = [];
    public function __construct()
    {
        $this->data = [
            'header_title' => 'Students List',
            'getRecord' => [],
        ];
    }

    public function index(Request $request)
    {
        $this->data['getRecord'] = $this->getStudent($request);
        return view('admin.student.list')->with([
            'data' => $this->data,
        ]);
    }

    public function create()
    {
        $this->data['getClass'] = $this->getClass();
        $this->data['header_title'] = 'Add New Student';
        return view('admin.student.add')->with([
            'data' => $this->data,
        ]);
    }

    public function store(StoreStudentRequest  $request)
    {

        $profile_pic = "";
        if (!empty($request->file('profile_pic'))) {
            $extension = $request->file('profile_pic')->getClientOriginalExtension();
            $file = $request->file('profile_pic');
            $randomStr = date('Ymshis') . Str::random(20);
            $filename = strtolower($randomStr) . '.' . $extension;
            $file->storeAs('uploads', $filename, 'public');
            $profile_pic = $filename;
        }

        $student = User::create([
            'name' => $request->name,
            'admission_number' => $request->admission_number,
            'class_id' => $request->class_id,
            'gender' => $request->gender,
            'date_of_birth' => $request->date_of_birth,
            'admission_date' => $request->admission_date,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'user_type' => 3,
            'profile_pic' => $profile_pic,
            'created_by' => auth()->user()->id,
        ]);

        return redirect('/admin/student/list')->with('success', 'New Student is Added Successfully');
    }

    public function edit($id)
    {
        $this->data['getRecord'] = User::findOrFail($id);
        if (!empty($this->data['getRecord'])) {
            $this->data['getClass'] = $this->getClass();
            $this->data['header_title'] = 'Edit Admin';
            return view('admin.student.edit')->with([
                'data' => $this->data,
            ]);
        }
    }

    public function update(UpdateStudentRequest  $request, $id)
    {
        $student = User::findOrFail($id);

        if (!empty($request->file('profile_pic')))
        {
            if (!empty($student->profile_pic)) {
                // Use the getProfile method from FormHelper
                $imageUrl = FormHelper::getProfile($student->profile_pic);

                // Now you can use $imageUrl as needed, for example, unlink the file
                if (file_exists(public_path($imageUrl))) {
                    unlink(public_path($imageUrl));
                }
            }
            $extension = $request->file('profile_pic')->getClientOriginalExtension();
            $file = $request->file('profile_pic');
            $randomStr = date('Ymshis') . Str::random(20);
            $filename = strtolower($randomStr) . '.' . $extension;
            $file->storeAs('uploads', $filename, 'public');

            $student->profile_pic = $filename;
        }

        $student->fill([
            'name' => $request->name,
            'admission_number' => $request->admission_number,
            'class_id' => $request->class_id,
            'gender' => $request->gender,
            'date_of_birth' => $request->date_of_birth,
            'admission_date' => $request->admission_date,
            'email' => $request->email,
            'updated_by' => auth()->user()->id,
        ]);

        if ($request->filled('password')) {
            $student->password = Hash::make($request->password);
        }

        $student->save();

        return redirect('/admin/student/list')->with('success', 'Student information is updated successfully');
    }

    public function destroy($id)
    {
        try {
            $user = User::findOrFail($id);
            $user->delete();

            return redirect('/admin/student/list')->with('success', 'Student data is soft deleted successfully');
        }catch (ModelNotFoundException $exception) {
            return redirect('/admin/student/list')->with('error', $exception->getMessage());
        }catch(Exception $e) {
            return redirect('/admin/student/list')->with('error', $e->getMessage());
        }
    }

    public function deletedList()
    {
        try {
            // Fetch soft-deleted records with user_type = 3
            $softDeletedRecords = User::onlyTrashed()
                ->where('user_type', 3)
                ->get();

            return view('admin.student.deleted_list', compact('softDeletedRecords'));
        }catch (ModelNotFoundException $exception) {
            return redirect('/admin/student/list')->with('error', $exception->getMessage());
        }catch(Exception $e) {
            return redirect('/admin/student/list')->with('error', $e->getMessage());
        }
    }

    public function restore($id)
    {
        try {
            // Restore soft-deleted record with user_type = 3
            User::withTrashed()
                ->where('id', $id)
                ->where('user_type', 3)
                ->restore();

            return redirect('/admin/student/list')->with('success', 'Student data is restored successfully');
        }catch (ModelNotFoundException $exception) {
            return redirect('/admin/student/list')->with('error', $exception->getMessage());
        }catch(Exception $e) {
            return redirect('/admin/student/list')->with('error', $e->getMessage());
        }
    }

    public function forceDelete($id)
    {
        try {
            // Force delete soft-deleted record with user_type = 3
            User::withTrashed()
                ->where('id', $id)
                ->where('user_type', 3)
                ->forceDelete();

            return redirect('/admin/student/list')->with('success', 'Student data is permanently deleted');
        }catch (ModelNotFoundException $exception) {
            return redirect('/admin/student/list')->with('error', $exception->getMessage());
        }catch(Exception $e) {
            return redirect('/admin/student/list')->with('error', $e->getMessage());
        }
    }


    private function getStudent(Request $request)
    {
        $query =  User::where('users.user_type', '=', 3)
                        ->with('parent');

        if ($request->name != '') {
            $query->where('name', 'like', '%' . $request->name . '%');
        }
        if ($request->email != '') {
            $query->where('email', 'like', '%' . $request->email . '%');
        }
        if ($request->date != '') {
            $query->whereDate('created_at', $request->date);
        }

        $paginator = $query->orderBy('id', 'desc')->paginate($this->pagination);

        $paginator->appends([
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'date' => $request->get('date'),
        ]);
        return $paginator;
    }

    private function getClass()
    {
        $record = ClassModel::select('class.*')
            ->whereNull('class.deleted_at')
            ->where('class.status', '=', '1')
            ->orderBy('class.name', 'asc')
            ->get();

        return $record;
    }

}
