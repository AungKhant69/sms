<?php

namespace App\Http\Controllers;

use Exception;
use App\Helper\FormHelper;
use App\Models\ClassModel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ClassController extends Controller
{
    public $pagination = '';
    public $data = [];
    public function __construct()
    {
        $config = FormHelper::getConfig();
        $this->pagination = $config['paginate'];
        $this->data = [
            'header_title' => 'Class List',
            'getRecord' => [],
        ];
    }

    public function index(Request $request)
    {
        $this->data['getRecord'] = $this->getList($request);
        return view('admin.class.list')->with([
            'data' => $this->data,
        ]);
    }

    public function create()
    {
        $this->data['header_title'] = 'Add New Class';
        return view('admin.class.add')->with([
            'data' => $this->data,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'required|in:1,0',
        ]);

        try {
            $class = ClassModel::create([
                'name' => $request->name,
                'fees_amount' => $request->fees_amount,
                'status' => $request->status,
                'created_by' => auth()->user()->id,
            ]);

            return redirect('/admin/class/list')->with('success', 'New Class is Added Successfully');
        } catch (ModelNotFoundException $exception) {
            return redirect('/admin/class/list')->with('error', $exception->getMessage());
        } catch (Exception $e) {
            return redirect('/admin/class/list')->with('error', $e->getMessage());
        }
    }

    public function edit($id)
    {

        $this->data['getRecord'] = ClassModel::findOrFail($id);
        if (!empty($this->data['getRecord'])) {
            $this->data['header_title'] = 'Edit Class';
            return view('admin.class.edit')->with([
                'data' => $this->data,
            ]);
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'string|max:255',
            'status' => 'required|in:1,0',
            'fees_amount' => [
                'required',
                'numeric',
                'between:1.00,9999999.99',
            ],
        ]);

        try {
            $class = ClassModel::findOrFail($id);

            $class->update([
                'name' => $request->name,
                'fees_amount' => $request->fees_amount,
                'status' => $request->status,
                'updated_by' => auth()->user()->id,
            ]);

            return redirect('/admin/class/list')->with('success', 'Class is Updated Successfully');
        } catch (ModelNotFoundException $exception) {
            return redirect('/admin/class/list')->with('error', $exception->getMessage());
        } catch (Exception $e) {
            return redirect('/admin/class/list')->with('error', $e->getMessage());
        }
    }


    public function destroy($id)
    {
        try {
            $class = ClassModel::findOrFail($id);
            $class->delete();

            return redirect('/admin/class/list')->with('success', 'Class is soft Deleted Successfully');
        } catch (ModelNotFoundException $exception) {
            return redirect('/admin/class/list')->with('error', $exception->getMessage());
        } catch (Exception $e) {
            return redirect('/admin/class/list')->with('error', $e->getMessage());
        }
    }

    public function deletedList()
    {
        try {
            $softDeletedRecords = ClassModel::onlyTrashed()->get();

            return view('admin.class.deleted_list', compact('softDeletedRecords'));
        } catch (ModelNotFoundException $exception) {
            return redirect('/admin/class/list')->with('error', $exception->getMessage());
        } catch (Exception $e) {
            return redirect('/admin/class/list')->with('error', $e->getMessage());
        }
    }

    public function restore($id)
    {
        try {
            ClassModel::withTrashed()->where('id', $id)->restore();

            return redirect('/admin/class/list')->with('success', 'Class data is restored successfully');
        } catch (ModelNotFoundException $exception) {
            return redirect('/admin/class/list')->with('error', $exception->getMessage());
        } catch (Exception $e) {
            return redirect('/admin/class/list')->with('error', $e->getMessage());
        }
    }

    public function forceDelete($id)
    {
        try {
            ClassModel::withTrashed()->where('id', $id)->forceDelete();

            return redirect('/admin/class/list')->with('success', 'Class data is permanently deleted');
        } catch (ModelNotFoundException $exception) {
            return redirect('/admin/class/list')->with('error', $exception->getMessage());
        } catch (Exception $e) {
            return redirect('/admin/class/list')->with('error', $e->getMessage());
        }
    }

    private function getList(Request $request)
    {
        $query  = ClassModel::with('createdBy', 'updatedBy');
        if (!empty($request->get('name'))) {
            $query = $query->where('class.name', 'like', '%' . $request->get('name') . '%');
        }

        if (!empty($request->get('date'))) {
            $query = $query->whereDate('class.created_at', '=', $request->get('date'));
        }

        $paginator = $query->orderBy('class.id', 'desc')->paginate($this->pagination);
        $paginator->appends([
            'name' => $request->get('name'),
            'date' => $request->get('date'),
        ]);
        return $paginator;
    }
}
