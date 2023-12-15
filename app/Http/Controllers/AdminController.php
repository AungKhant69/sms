<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\StoreAdminRequest;
use App\Http\Requests\UpdateAdminRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class AdminController extends Controller
{
    public $pagination = 20;
    public $data = [];
    public function __construct()
    {
        $this->data = [
            'header_title' => 'Admin List',
            'getRecord' => [],
        ];
    }

    public function index(Request $request)
    {
        $this->data['getRecord'] = $this->getList($request);
        return view('admin.admin.list')->with([
            'data' => $this->data,
        ]);
    }

    public function create()
    {
        $this->data['header_title'] = 'Add New Admin';
        return view('admin.admin.add')->with([
            'data' => $this->data,
        ]);
    }

    public function store(StoreAdminRequest $request)
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

        $admin = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'profile_pic' => $profile_pic,
            'user_type' => 1,
            'created_by' => auth()->user()->id,
            'password' => Hash::make($request->password),
        ]);

        return redirect('/admin/admin/list')->with('success', 'New Admin is Added Successfully');
    }

    public function edit($id)
    {
        $this->data['getRecord'] = User::findOrFail($id);
        if (!empty($this->data['getRecord'])) {
            $this->data['header_title'] = 'Edit Admin';
            return view('admin.admin.edit')->with([
                'data' => $this->data,
            ]);
        }
    }

    public function update(UpdateAdminRequest $request, $id)
    {
        try {
            $admin = User::findOrFail($id);

            if (!empty($request->file('profile_pic'))) {

                if (!empty($admin->profile_pic)) {
                    $filename = $admin->profile_pic;
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

                $admin->profile_pic = $filename;
            }

            $admin->fill([
                'name' => $request->name,
                'email' => $request->email,
                'updated_by' => auth()->user()->id,
            ]);

            if ($request->filled('password')) {
                $admin->password = Hash::make($request->password);
            }

            $admin->save();

            return redirect('/admin/admin/list')->with('success', 'Admin data is updated successfully');
        } catch (ModelNotFoundException $exception) {
            return redirect('/admin/admin/list')->with('error', $exception->getMessage());
        } catch (Exception $e) {
            return redirect('/admin/admin/list')->with('error', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $user = User::findOrFail($id);
            $user->delete();

            return redirect('/admin/admin/list')->with('success', 'Admin data is soft deleted successfully');
        } catch (ModelNotFoundException $exception) {
            return redirect('/admin/admin/list')->with('error', $exception->getMessage());
        } catch (Exception $e) {
            return redirect('/admin/admin/list')->with('error', $e->getMessage());
        }
    }

    public function deletedList()
    {
        try {
            // Fetch soft-deleted records with user_type = 1
            $softDeletedRecords = User::onlyTrashed()
                ->where('user_type', 1)
                ->get();

            return view('admin.admin.deleted_list', compact('softDeletedRecords'));
        } catch (ModelNotFoundException $exception) {
            return redirect('/admin/admin/list')->with('error', $exception->getMessage());
        } catch (Exception $e) {
            return redirect('/admin/admin/list')->with('error', $e->getMessage());
        }
    }

    public function restore($id)
    {
        try {
            // Restore soft-deleted record with user_type = 1
            User::withTrashed()
                ->where('id', $id)
                ->where('user_type', 1)
                ->restore();

            return redirect('/admin/admin/list')->with('success', 'Admin data is restored successfully');
        } catch (ModelNotFoundException $exception) {
            return redirect('/admin/admin/list')->with('error', $exception->getMessage());
        } catch (Exception $e) {
            return redirect('/admin/admin/list')->with('error', $e->getMessage());
        }
    }

    public function forceDelete($id)
    {
        try {
            // Force delete soft-deleted record with user_type = 1
            User::withTrashed()
                ->where('id', $id)
                ->where('user_type', 1)
                ->forceDelete();

            return redirect('/admin/admin/list')->with('success', 'Admin data is permanently deleted');
        } catch (ModelNotFoundException $exception) {
            return redirect('/admin/admin/list')->with('error', $exception->getMessage());
        } catch (Exception $e) {
            return redirect('/admin/admin/list')->with('error', $e->getMessage());
        }
    }

    private function getList(Request $request)
    {
        $query = User::with('createdBy', 'updatedBy')
            ->where('user_type', '=', 1)
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
}
