<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use App\Helper\FormHelper;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\StoreParentRequest;
use App\Http\Requests\UpdateParentRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;


class ParentController extends Controller
{
    public $pagination = 5;
    public $data = [];
    public function __construct()
    {
        $this->data = [
            'header_title' => 'Parents List',
            'getRecord' => [],
        ];
    }

    public function index(Request $request)
    {
        $this->data['getRecord'] = $this->getParent($request);
        return view('admin.parent.list')->with([
            'data' => $this->data,
        ]);
    }

    public function create()
    {
        $this->data['header_title'] = 'Add New Student';
        return view('admin.parent.add')->with([
            'data' => $this->data,
        ]);
    }

    public function store(StoreParentRequest  $request)
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

        $parent = User::create([
            'name' => $request->name,
            'gender' => $request->gender,
            'address' => $request->address,
            'phone_number' => $request->phone_number,
            'date_of_birth' => $request->date_of_birth,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'user_type' => 4,
            'profile_pic' => $profile_pic,
            'created_by' => auth()->user()->id,
        ]);

        return redirect('/admin/parent/list')->with('success', 'New Parent is Added Successfully');
    }

    public function edit($id)
    {
        $this->data['getRecord'] = User::findOrFail($id);
        if (!empty($this->data['getRecord'])) {
            $this->data['header_title'] = 'Edit Parent';
            return view('admin.parent.edit')->with([
                'data' => $this->data,
            ]);
        }
    }

    public function update(UpdateParentRequest  $request, $id)
    {
        $parent = User::findOrFail($id);

        if (!empty($request->file('profile_pic'))) {
            if (!empty($parent->profile_pic)) {
                // Use the getProfile method from FormHelper
                $imageUrl = FormHelper::getProfile($parent->profile_pic);

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

            $parent->profile_pic = $filename;
        }

        $parent->fill([
            'name' => $request->name,
            'gender' => $request->gender,
            'address' => $request->address,
            'phone_number' => $request->phone_number,
            'date_of_birth' => $request->date_of_birth,
            'email' => $request->email,
            'updated_by' => auth()->user()->id,
        ]);

        if ($request->filled('password')) {
            $parent->password = Hash::make($request->password);
        }

        $parent->save();

        return redirect('/admin/parent/list')->with('success', 'Parent information is updated successfully');
    }

    public function destroy($id)
    {
        try {
            $user = User::findOrFail($id);
            $user->delete();

            return redirect('/admin/parent/list')->with('success', 'Parent data is soft deleted successfully');
        } catch (ModelNotFoundException $exception) {
            return redirect('/admin/parent/list')->with('error', $exception->getMessage());
        } catch (Exception $e) {
            return redirect('/admin/parent/list')->with('error', $e->getMessage());
        }
    }

    public function deletedList()
    {
        try {
            // Fetch soft-deleted records with user_type = 4
            $softDeletedRecords = User::onlyTrashed()
                ->where('user_type', 4)
                ->get();

            return view('admin.parent.deleted_list', compact('softDeletedRecords'));
        } catch (ModelNotFoundException $exception) {
            return redirect('/admin/parent/list')->with('error', $exception->getMessage());
        } catch (Exception $e) {
            return redirect('/admin/parent/list')->with('error', $e->getMessage());
        }
    }

    public function restore($id)
    {
        try {
            // Restore soft-deleted record with user_type = 4
            User::withTrashed()
                ->where('id', $id)
                ->where('user_type', 4)
                ->restore();

            return redirect('/admin/parent/list')->with('success', 'Parent data is restored successfully');
        } catch (ModelNotFoundException $exception) {
            return redirect('/admin/parent/list')->with('error', $exception->getMessage());
        } catch (Exception $e) {
            return redirect('/admin/parent/list')->with('error', $e->getMessage());
        }
    }

    public function forceDelete($id)
    {
        try {
            // Force delete soft-deleted record with user_type = 4
            User::withTrashed()
                ->where('id', $id)
                ->where('user_type', 4)
                ->forceDelete();

            return redirect('/admin/parent/list')->with('success', 'Parent data is permanently deleted');
        } catch (ModelNotFoundException $exception) {
            return redirect('/admin/parent/list')->with('error', $exception->getMessage());
        } catch (Exception $e) {
            return redirect('/admin/parent/list')->with('error', $e->getMessage());
        }
    }

    private function getParent(Request $request)
    {
        $query =  User::where('users.user_type', '=', 4)
            ->whereNull('users.deleted_at');

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

    public function myStudent($id, Request $request)
    {
        $this->data['parent_id'] = $id;
        $this->data['getRecord'] = $this->getRecord($request);
        $this->data['getMyStudent'] = $this->getMyStudent($id);
        $this->data['header_title'] = 'Parent Student Relationships';
        return view('admin.parent.myStudent')->with([
            'data' => $this->data,
        ]);
    }

    private function getRecord(Request $request)
    {
        $query =  User::where('users.user_type', '=', 3)
                        ->whereNull('users.parent_id')
                        ->with('parent');

        if ($request->name != '') {
            $query->where('name', 'like', '%' . $request->name . '%');
        }
        if ($request->email != '') {
            $query->where('email', 'like', '%' . $request->email . '%');
        }

        $paginator = $query->orderBy('id', 'desc')->paginate($this->pagination);

        $paginator->appends([
            'name' => $request->get('name'),
            'email' => $request->get('email'),
        ]);
        return $paginator;
    }

    private function getMyStudent($parent_id)
    {
        $query =  User::where('users.user_type', '=', 3)
            ->where('users.parent_id', '=', $parent_id)
            ->with('parent');

        $paginator = $query->orderBy('id', 'desc')->paginate($this->pagination);
        return $paginator;
    }

    public function assignStudentToParent($student_id, $parent_id)
    {
        $student = User::findOrFail($student_id);
        $student->parent_id = $parent_id;
        $student->save();

        return redirect()->back()->with('success', 'Student Successfully Assigned to Parent');
    }

    public function removeStudentParent($student_id)
    {
        $student = User::findOrFail($student_id);
        $student->update(['parent_id' => null]);

        return redirect()->back()->with('success', 'Student Successfully Removed from Parent');
    }
}
