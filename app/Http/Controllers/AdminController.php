<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class AdminController extends Controller
{
    public $pagination = 90;
    public $data = [];
    public function __construct()
    {
        $this->data = [
            'header_title' => 'Admin List',
            'getRecord' => [],
        ];
    }

    public function list(Request $request)
    {
        $this->data['getRecord'] = $this->getList($request);
        return view('admin.admin.list')->with([
            'data' => $this->data,
        ]);

    }

    public function add()
    {
        $data['header_title'] = 'Add New Admin';
        return view('admin.admin.add', $data);
    }

    public function insert(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:users', //users = table name
        ]);

        $user = new User();
        $user->name = trim($request->name);
        $user->email = trim($request->email);
        $user->password = Hash::make($request->password);
        $user->user_type = 1;
        $user->save();

        return redirect('/admin/admin/list')->with('success', 'New Admin is Added Successfully');
    }

    public function edit($id)
    {
        $data['getRecord'] = User::getSingle($id);
        if (!empty($data['getRecord'])) {
            $data['header_title'] = 'Edit Admin';
            return view('admin.admin.edit', $data);
        } else {
            abort(404);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'email' => 'required|email|unique:users,email,' . $id,
            ]);

            $user = User::getSingle($id);

            if (!$user) {
                return redirect('/admin/admin/list')->with('error', 'Admin not found.');
            }

            $user->name = trim($request->name);
            $user->email = trim($request->email);

            if (!empty($request->password)) {
                $user->password = Hash::make($request->password);
            }

            $user->save();

            return redirect('/admin/admin/list')->with('success', 'Admin data is updated successfully');
        } catch (\Exception $e) {
            return redirect('/admin/admin/list')->with('error', 'Error updating admin data: ' . $e->getMessage());
        }
    }

    public function delete($id)
    {
        try {
            $user = User::findOrFail($id);
            $user->delete();

            return redirect('/admin/admin/list')->with('success', 'Admin data is soft deleted successfully');
        } catch (ModelNotFoundException $e) {
            return redirect('/admin/admin/list')->with('error', 'Admin data not found');
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
        } catch (\Exception $e) {
            return redirect('/admin/admin/list')->with('error', 'Error retrieving soft-deleted records');
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
            return redirect('/admin/admin/list')->with('error', 'Admin data not found');
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
            return redirect('/admin/admin/list')->with('error', 'Admin data not found');
        }
    }

    private function getList(Request $request)
    {
        $query = User::select('users.*')->where('user_type', '=', 0);
        if ($request->name != '') {
            $query->where('name', 'like', '%' . $request->name . '%');
        }
        if ($request->email != '') {
            $query->where('email', 'like', '%' . $request->email . '%');
        }
        if ($request->date != '') {
            $query->whereDate('created_at', $request->date);
        }
        $query->whereNull('deleted_at');

        $paginator = $query->orderBy('id', 'desc')->paginate($this->pagination);

        $paginator->appends([
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'date' => $request->get('date'),
        ]);
        return $paginator;

    }
}
