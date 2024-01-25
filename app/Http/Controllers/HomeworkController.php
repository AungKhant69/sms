<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use App\Helper\FormHelper;
use Illuminate\Support\Str;
use App\Models\SubjectModel;
use Illuminate\Http\Request;
use App\Models\HomeworkModel;
use App\Models\ClassSubjectModel;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\StoreHomeworkRequest;
use App\Http\Requests\UpdateHomeworkRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class HomeworkController extends Controller
{
    public $pagination = '';
    public $data = [];
    public function __construct()
    {
        $config = FormHelper::getConfig();
        $this->pagination = $config['paginate'];
        $this->data = [
            'header_title' => 'Homework',
            'getRecord' => [],
        ];
    }

    public function index(Request $request)
    {
        $this->data['getRecord'] = $this->getRecord($request);
        return view('admin.student_management.homeworkList')->with([
            'data' => $this->data,
        ]);
    }

    public function index_teacher_side(Request $request)
    {
        $this->data['getRecord'] = $this->getRecord($request);
        return view('teacher.student_management.homeworkList')->with([
            'data' => $this->data,
        ]);
    }

    public function index_student_side(Request $request)
    {
        $this->data['getRecord'] = $this->getRecord($request);
        $this->data['getRecord'] = $this->getRecordStudent(Auth::user()->class_id);

        $this->data['header_title'] = 'My Homework';
        return view('student.my_academic_info.my_homework')->with([
            'data' => $this->data,
        ]);
    }

    public function create()
    {
        $this->data['getSubject'] = $this->getSubject();

        $this->data['header_title'] = 'Add Homework';
        return view('admin.student_management.homeworkCreate')->with([
            'data' => $this->data,
        ]);
    }

    public function create_teacher_side()
    {
        $this->data['getSubject'] = $this->getSubject();

        $this->data['header_title'] = 'Add Homework';
        return view('teacher.student_management.homeworkCreate')->with([
            'data' => $this->data,
        ]);
    }

    public function store(StoreHomeworkRequest $request)
    {
        // dd($request->all());
        // $document_file = "";
        // if (!empty($request->file('document_file'))) {
        //     $extension = $request->file('document_file')->getClientOriginalExtension();
        //     $file = $request->file('document_file');
        //     $randomStr = date('Ymshis') . Str::random(20);
        //     $filename = strtolower($randomStr) . '.' . $extension;
        //     $file->storeAs('homework', $filename, 'public');
        //     $document_file = $filename;
        // }

        if (!empty($request->file('document_file'))) {
            $ext = $request->file('document_file')->getClientOriginalExtension();
            $file = $request->file('document_file');
            $randomStr = date('Ymshis') . Str::random(20);
            $filename = strtolower($randomStr) . '.' . $ext;
            $file->move('uploads/homework', $filename);
        } else {
            $filename = null;
        }

        $homework = HomeworkModel::create([
            'subject_id' => $request->subject_id,
            'homework_date' => $request->homework_date,
            'deadline' => $request->deadline,
            'document_file' => $filename,
            'description' => $request->description,
            'created_by' => auth()->user()->id,
        ]);

        return redirect('/admin/student_management/homework')->with('success', 'New Homework is Added Successfully');
    }

    public function store_teacher_side(StoreHomeworkRequest $request)
    {
        // dd($request->all());
        // $document_file = "";
        // if (!empty($request->file('document_file'))) {
        //     $extension = $request->file('document_file')->getClientOriginalExtension();
        //     $file = $request->file('document_file');
        //     $randomStr = date('Ymshis') . Str::random(20);
        //     $filename = strtolower($randomStr) . '.' . $extension;
        //     $file->storeAs('homework', $filename, 'public');
        //     $document_file = $filename;
        // }

        if (!empty($request->file('document_file'))) {
            $ext = $request->file('document_file')->getClientOriginalExtension();
            $file = $request->file('document_file');
            $randomStr = date('Ymshis') . Str::random(20);
            $filename = strtolower($randomStr) . '.' . $ext;
            $file->move('uploads/homework', $filename);
        } else {
            $filename = null;
        }

        $homework = HomeworkModel::create([
            'subject_id' => $request->subject_id,
            'homework_date' => $request->homework_date,
            'deadline' => $request->deadline,
            'document_file' => $filename,
            'description' => $request->description,
            'created_by' => auth()->user()->id,
        ]);

        return redirect('/teacher/student_management/homework')->with('success', 'New Homework is Added Successfully');
    }

    public function edit($id)
    {
        $this->data['getRecord'] = HomeworkModel::findOrFail($id);
        $this->data['getSubject'] = $this->getSubject();

        // Pass the current document file path to the view
        $this->data['documentFilePath'] = $this->data['getRecord']->document_file;

        $this->data['header_title'] = 'Edit Homework';
        return view('admin.student_management.homeworkEdit')->with([
            'data' => $this->data,
        ]);
    }

    public function edit_teacher_side($id)
    {
        $this->data['getRecord'] = HomeworkModel::findOrFail($id);
        $this->data['getSubject'] = $this->getSubject();

        // Pass the current document file path to the view
        $this->data['documentFilePath'] = $this->data['getRecord']->document_file;

        $this->data['header_title'] = 'Edit Homework';
        return view('teacher.student_management.homeworkEdit')->with([
            'data' => $this->data,
        ]);
    }

    public function update(UpdateHomeworkRequest $request, $id)
    {
        $homework = HomeworkModel::findOrFail($id);

        if ($request->hasFile('document_file')) {
            // Handle the new file upload
            $newDocumentFile = $request->file('document_file');
            $randomStr = date('Ymshis') . Str::random(20);
            $filename = strtolower($randomStr) . '.' . $newDocumentFile->getClientOriginalExtension();

            // Delete the old file if it exists
            if ($homework->document_file) {
                // Assuming the old file is in the same 'uploads/homework' directory
                File::delete('uploads/homework/' . $homework->document_file);
            }

            // Move the new file to the 'uploads/homework' directory
            $newDocumentFile->move('uploads/homework', $filename);

            // Update the document_file field in the database
            $homework->document_file = $filename;
        }

        $homework->fill([
            'subject_id' => $request->input('subject_id'),
            'homework_date' => $request->input('homework_date'),
            'deadline' => $request->input('deadline'),
            'description' => $request->input('description'),
            'updated_by' => auth()->user()->id,
        ]);

        $homework->save();

        return redirect('admin/student_management/homework')->with('success', 'Homework updated successfully');
    }

    public function update_teacher_side(UpdateHomeworkRequest $request, $id)
    {
        $homework = HomeworkModel::findOrFail($id);

        if ($request->hasFile('document_file')) {
            // Handle the new file upload
            $newDocumentFile = $request->file('document_file');
            $randomStr = date('Ymshis') . Str::random(20);
            $filename = strtolower($randomStr) . '.' . $newDocumentFile->getClientOriginalExtension();

            // Delete the old file if it exists
            if ($homework->document_file) {
                // Assuming the old file is in the same 'uploads/homework' directory
                File::delete('uploads/homework/' . $homework->document_file);
            }

            // Move the new file to the 'uploads/homework' directory
            $newDocumentFile->move('uploads/homework', $filename);

            // Update the document_file field in the database
            $homework->document_file = $filename;
        }

        $homework->fill([
            'subject_id' => $request->input('subject_id'),
            'homework_date' => $request->input('homework_date'),
            'deadline' => $request->input('deadline'),
            'description' => $request->input('description'),
            'updated_by' => auth()->user()->id,
        ]);

        $homework->save();

        return redirect('teacher/student_management/homework')->with('success', 'Homework updated successfully');
    }

    public function destroy($id)
    {
        try {
            $homework = HomeworkModel::findOrFail($id);
            $homework->delete();

            return redirect('/admin/student_management/homework')->with('success', 'Homework is soft Deleted Successfully');
        } catch (ModelNotFoundException $exception) {
            return redirect('/admin/student_management/homework')->with('error', $exception->getMessage());
        } catch (Exception $e) {
            return redirect('/admin/student_management/homework')->with('error', $e->getMessage());
        }
    }

    public function destroy_teacher_side($id)
    {
        try {
            $homework = HomeworkModel::findOrFail($id);
            $homework->delete();

            return redirect('/teacher/student_management/homework')->with('success', 'Homework is soft Deleted Successfully');
        } catch (ModelNotFoundException $exception) {
            return redirect('/teacher/student_management/homework')->with('error', $exception->getMessage());
        } catch (Exception $e) {
            return redirect('/teacher/student_management/homework')->with('error', $e->getMessage());
        }
    }

    public function deletedList()
    {
        try {
            $softDeletedRecords = HomeworkModel::onlyTrashed()->get();

            return view('admin.student_management.homework_deleted_list', compact('softDeletedRecords'));
        } catch (ModelNotFoundException $exception) {
            return redirect('/admin/student_management/homework')->with('error', $exception->getMessage());
        } catch (Exception $e) {
            return redirect('/admin/student_management/homework')->with('error', $e->getMessage());
        }
    }

    public function deletedList_teacher_side()
    {
        try {
            $softDeletedRecords = HomeworkModel::onlyTrashed()->get();

            return view('teacher.student_management.homework_deleted_list', compact('softDeletedRecords'));
        } catch (ModelNotFoundException $exception) {
            return redirect('/teacher/student_management/homework')->with('error', $exception->getMessage());
        } catch (Exception $e) {
            return redirect('/teacher/student_management/homework')->with('error', $e->getMessage());
        }
    }

    public function Restore($id)
    {
        try {
            HomeworkModel::withTrashed()->where('id', $id)->restore();

            return redirect('/admin/student_management/homework')->with('success', 'Homework data is restored successfully');
        } catch (ModelNotFoundException $exception) {
            return redirect('/admin/student_management/homework')->with('error', $exception->getMessage());
        } catch (Exception $e) {
            return redirect('/admin/student_management/homework')->with('error', $e->getMessage());
        }
    }

    public function Restore_teacher_side($id)
    {
        try {
            HomeworkModel::withTrashed()->where('id', $id)->restore();

            return redirect('/teacher/student_management/homework')->with('success', 'Homework data is restored successfully');
        } catch (ModelNotFoundException $exception) {
            return redirect('/teacher/student_management/homework')->with('error', $exception->getMessage());
        } catch (Exception $e) {
            return redirect('/teacher/student_management/homework')->with('error', $e->getMessage());
        }
    }

    public function ForceDelete($id)
    {
        try {
            $homework = HomeworkModel::withTrashed()->find($id);

            if (!$homework) {
                throw new ModelNotFoundException("Homework with ID {$id} not found.");
            }

            // Delete the file if it exists
            if ($homework->document_file) {
                // Assuming the old file is in the same 'uploads/homework' directory
                File::delete('uploads/homework/' . $homework->document_file);
            }

            // Permanently delete the homework
            $homework->forceDelete();

            return redirect('/admin/student_management/homework')->with('success', 'Homework data is permanently deleted');
        } catch (ModelNotFoundException $exception) {
            return redirect('/admin/student_management/homework')->with('error', $exception->getMessage());
        } catch (Exception $e) {
            return redirect('/admin/student_management/homework')->with('error', $e->getMessage());
        }
    }

    public function ForceDelete_teacher_side($id)
    {
        try {
            $homework = HomeworkModel::withTrashed()->find($id);

            if (!$homework) {
                throw new ModelNotFoundException("Homework with ID {$id} not found.");
            }

            // Delete the file if it exists
            if ($homework->document_file) {
                // Assuming the old file is in the same 'uploads/homework' directory
                File::delete('uploads/homework/' . $homework->document_file);
            }

            // Permanently delete the homework
            $homework->forceDelete();

            return redirect('/teacher/student_management/homework')->with('success', 'Homework data is permanently deleted');
        } catch (ModelNotFoundException $exception) {
            return redirect('/teacher/student_management/homework')->with('error', $exception->getMessage());
        } catch (Exception $e) {
            return redirect('/teacher/student_management/homework')->with('error', $e->getMessage());
        }
    }

    private function getSubject()
    {
        $record = SubjectModel::select('id', 'name')
            ->where('subject.status', '=', '1')
            ->orderBy('subject.name', 'asc')
            ->get();

        return $record;
    }

    private function getRecord(Request $request)
    {
        $query =  HomeworkModel::with('subjectData', 'createdBy', 'updatedBy');

        if (!empty($request->get('class_id'))) {
            $query = $query->where('attendance.class_id', '=', $request->get('class_id'));
        }

        if (!empty($request->get('attendance_date'))) {
            $query = $query->where('attendance.attendance_date', '=', $request->get('attendance_date'));
        }

        if (!empty($request->get('attendance_type'))) {
            $query = $query->where('attendance.attendance_type', '=', $request->get('attendance_type'));
        }

        // Add a condition for the student relationship
        if (!empty($request->get('student_name'))) {
            $query = $query->whereHas('student', function ($subQuery) use ($request) {
                $subQuery->where('users.name', 'LIKE', '%' . $request->get('student_name') . '%');
            });
        }

        $paginator = $query->orderBy('id', 'desc')->paginate($this->pagination);
        return $paginator;
    }

    private function getRecordStudent($class_id)
    {
        $subjectIds = ClassSubjectModel::where('class_id', $class_id)->pluck('subject_id');

        $homeworks = HomeworkModel::whereIn('subject_id', $subjectIds)
            ->with('subjectData', 'classData', 'student')
            ->orderBy('id', 'desc')
            ->paginate($this->pagination);

        return $homeworks;
    }

    // Parent side
    public function homework_parent_side($student_id)
    {
        $getStudent = User::findOrFail($student_id);
        $this->data['getRecord'] = $this->getRecordStudent($getStudent->class_id, $getStudent->id);

        $this->data['header_title'] = 'Check Homework';
        return view('parent.my_student_homework')->with([
            'data' => $this->data,
        ]);
    }
}
