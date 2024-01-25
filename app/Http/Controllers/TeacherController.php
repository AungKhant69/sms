<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use App\Helper\FormHelper;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\StoreTeacherRequest;
use App\Http\Requests\UpdateParentRequest;
use App\Http\Requests\UpdateSettingsRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class TeacherController extends Controller
{
    public $pagination = '';
    public $data = [];
    public function __construct()
    {
        $config = FormHelper::getConfig();
        $this->pagination = $config['paginate'];
        $this->data = [
            'header_title' => 'Teachers List',
            'getRecord' => [],
        ];
    }

    public function index(Request $request)
    {
        $this->data['getRecord'] = $this->getTeacher($request);
        return view('admin.teacher.list')->with([
            'data' => $this->data,
        ]);
    }

    public function index_student_side()
    {
        $this->data['contactTeacher'] = $this->contactTeacher();
        $this->data['header_title'] = 'Contact Teacher';

        return view('student.contact_teacher')->with([
            'data' => $this->data,
        ]);
    }

    public function index_parent_side()
    {
        $this->data['contactTeacher'] = $this->contactTeacher();
        $this->data['header_title'] = 'Contact Teacher';

        return view('parent.contact_teacher')->with([
            'data' => $this->data,
        ]);
    }

    public function create()
    {
        $this->data['header_title'] = 'Add New Teacher';
        return view('admin.teacher.add')->with([
            'data' => $this->data,
        ]);
    }

    public function store(StoreTeacherRequest  $request)
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

        $teacher = User::create([
            'name' => $request->name,
            'gender' => $request->gender,
            'address' => $request->address,
            'phone_number' => $request->phone_number,
            'date_of_birth' => $request->date_of_birth,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'user_type' => 2,
            'profile_pic' => $profile_pic,
            'created_by' => auth()->user()->id,
        ]);

        return redirect('/admin/teacher/list')->with('success', 'New Teacher is Added Successfully');
    }

    public function edit($id)
    {
        $this->data['getRecord'] = User::findOrFail($id);
        if (!empty($this->data['getRecord'])) {
            $this->data['header_title'] = 'Edit Teacher';
            return view('admin.teacher.edit')->with([
                'data' => $this->data,
            ]);
        }
    }

    public function update(UpdateParentRequest  $request, $id)
    {
        $teacher = User::findOrFail($id);

        if (!empty($request->file('profile_pic'))) {

            if (!empty($teacher->profile_pic)) {
                $filename = $teacher->profile_pic;
                $path = 'storage/uploads/' . $filename;

                if (file_exists(public_path($path))) {
                    unlink(public_path($path));
                }
            }
            $extension = $request->file('profile_pic')->getClientOriginalExtension();
            $file = $request->file('profile_pic');
            $randomStr = date('Ymshis') . Str::random(20);
            $filename = strtolower($randomStr) . '.' . $extension;
            $file->storeAs('uploads', $filename, 'public');

            $teacher->profile_pic = $filename;
        }

        $teacher->fill([
            'name' => $request->name,
            'gender' => $request->gender,
            'address' => $request->address,
            'phone_number' => $request->phone_number,
            'date_of_birth' => $request->date_of_birth,
            'email' => $request->email,
            'updated_by' => auth()->user()->id,
        ]);

        if ($request->filled('password')) {
            $teacher->password = Hash::make($request->password);
        }

        $teacher->save();

        return redirect('/admin/teacher/list')->with('success', 'Teacher information is updated successfully');
    }

    public function destroy($id)
    {
        try {
            $user = User::findOrFail($id);
            $user->delete();

            return redirect('/admin/teacher/list')->with('success', 'Teacher data is soft deleted successfully');
        }catch (ModelNotFoundException $exception) {
            return redirect('/admin/teacher/list')->with('error', $exception->getMessage());
        }catch(Exception $e) {
            return redirect('/admin/teacher/list')->with('error', $e->getMessage());
        }
    }

    public function deletedList()
    {
        try {
            // Fetch soft-deleted records with user_type = 2
            $softDeletedRecords = User::onlyTrashed()
                ->where('user_type', 2)
                ->get();

            return view('admin.teacher.deleted_list', compact('softDeletedRecords'));
        } catch (ModelNotFoundException $exception) {
            return redirect('/admin/teacher/list')->with('error', $exception->getMessage());
        }catch(Exception $e) {
            return redirect('/admin/teacher/list')->with('error', $e->getMessage());
        }
    }

    public function restore($id)
    {
        try {
            // Restore soft-deleted record with user_type = 2
            User::withTrashed()
                ->where('id', $id)
                ->where('user_type', 2)
                ->restore();

            return redirect('/admin/teacher/list')->with('success', 'Teacher data is restored successfully');
        } catch (ModelNotFoundException $exception) {
            return redirect('/admin/teacher/list')->with('error', $exception->getMessage());
        }catch(Exception $e) {
            return redirect('/admin/teacher/list')->with('error', $e->getMessage());
        }
    }

    public function forceDelete($id)
    {
        try {
            // Force delete soft-deleted record with user_type = 2
            User::withTrashed()
                ->where('id', $id)
                ->where('user_type', 2)
                ->forceDelete();

            return redirect('/admin/teacher/list')->with('success', 'Teacher data is permanently deleted');
        } catch (ModelNotFoundException $exception) {
            return redirect('/admin/teacher/list')->with('error', $exception->getMessage());
        }catch(Exception $e) {
            return redirect('/admin/teacher/list')->with('error', $e->getMessage());
        }
    }

    private function getTeacher(Request $request)
    {
        $query =  User::where('users.user_type', '=', 2)
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

    // ********** Teacher Profile Settings ****************
    public function showSettings()
    {
        return view('admin.teacher.profile');
    }

    public function updateSettings(UpdateSettingsRequest $request)
    {
        $user = Auth::user();

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->name = $request->name;
        $user->email = $request->email;
        $user->date_format = $request->date_format;

        $user->save();

        return redirect()->back()->with('success', 'Profile updated.');
    }

    private function contactTeacher()
    {
        $teachers = User::where('user_type', 2)
        ->with(['assignClassTeacher.classData'])
        ->orderBy('name', 'asc')
        ->paginate($this->pagination);

    return $teachers;
    }
}
