<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ClassController;
use App\Http\Controllers\ParentController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ClassSubjectController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::get('/', [AuthController::class, 'login'])->name('login');
Route::post('login', [AuthController::class, 'authLogin']);
Route::get('logout', [AuthController::class, 'logout']);

Route::get('forgot-password', [AuthController::class, 'forgotpassword']);
Route::post('forgot-password', [AuthController::class, 'postForgotPassword']);

Route::get('reset/{token}', [AuthController::class, 'reset']);
Route::post('reset/{token}', [AuthController::class, 'postReset']);

// ******   Admin routes   ******//

Route::group(['middleware' => 'admin', 'prefix' => 'admin'], function () {
    Route::get('dashboard', [DashboardController::class, 'dashboard']);

    Route::get('admin/list', [AdminController::class, 'index'])->name('admin.index');
    Route::get('admin/add', [AdminController::class, 'create'])->name('admin.create');
    Route::post('admin/add', [AdminController::class, 'store'])->name('admin.store');
    Route::get('admin/edit/{id}', [AdminController::class, 'edit'])->name('admin.edit');
    Route::put('admin/edit/{id}', [AdminController::class, 'update'])->name('admin.update');
    Route::delete('admin/delete/{id}', [AdminController::class, 'destroy'])->name('admin.destroy');

    Route::get('admin/deleted_list', [AdminController::class, 'deletedList'])->name('admin.deletedList');
    Route::post('admin/restore/{id}', [AdminController::class, 'restore'])->name('admin.restore');
    Route::delete('admin/force-delete/{id}', [AdminController::class, 'forceDelete'])->name('admin.forceDelete');


    // student management routes
    Route::get('student/list', [StudentController::class, 'index'])->name('admin_student.index');
    Route::get('student/add', [StudentController::class, 'create'])->name('admin_student.create');
    Route::post('student/add', [StudentController::class, 'store'])->name('admin_student.store');
    Route::get('student/edit/{id}', [StudentController::class, 'edit'])->name('admin_student.edit');
    Route::put('student/edit/{id}', [StudentController::class, 'update'])->name('admin_student.update');
    Route::delete('student/delete/{id}', [StudentController::class, 'destroy'])->name('admin_student.destroy');

    Route::get('student/deleted_list', [StudentController::class, 'deletedList'])->name('admin_student.deletedList');
    Route::post('student/restore/{id}', [StudentController::class, 'restore'])->name('admin_student.restore');
    Route::delete('student/force-delete/{id}', [StudentController::class, 'forceDelete'])->name('admin_student.forceDelete');

    //parent management routes
    Route::get('parent/list', [ParentController::class, 'index'])->name('admin_parent.index');
    Route::get('parent/add', [ParentController::class, 'create'])->name('admin_parent.create');
    Route::post('parent/add', [ParentController::class, 'store'])->name('admin_parent.store');
    Route::get('parent/edit/{id}', [ParentController::class, 'edit'])->name('admin_parent.edit');
    Route::put('parent/edit/{id}', [ParentController::class, 'update'])->name('admin_parent.update');
    Route::delete('parent/delete/{id}', [ParentController::class, 'destroy'])->name('admin_parent.destroy');

    Route::get('parent/my_student/{id}', [ParentController::class, 'myStudent'])->name('admin_parent.myStudent');
    Route::put('parent/assign_student_parent/{student_id}/{parent_id}', [ParentController::class, 'assignStudentToParent'])->name('admin_parent.assignStudentToParent');
    Route::put('parent/remove_student_parent/{student_id}', [ParentController::class, 'removeStudentParent'])->name('admin_parent.removeStudentParent');

    Route::get('parent/deleted_list', [ParentController::class, 'deletedList'])->name('admin_parent.deletedList');
    Route::post('parent/restore/{id}', [ParentController::class, 'restore'])->name('admin_parent.restore');
    Route::delete('parent/force-delete/{id}', [ParentController::class, 'forceDelete'])->name('admin_parent.forceDelete');

    //teacher management routes
    Route::get('teacher/list', [TeacherController::class, 'index'])->name('admin_teacher.index');
    Route::get('teacher/add', [TeacherController::class, 'create'])->name('admin_teacher.create');
    Route::post('teacher/add', [TeacherController::class, 'store'])->name('admin_teacher.store');
    Route::get('teacher/edit/{id}', [TeacherController::class, 'edit'])->name('admin_teacher.edit');
    Route::put('teacher/edit/{id}', [TeacherController::class, 'update'])->name('admin_teacher.update');
    Route::delete('teacher/delete/{id}', [TeacherController::class, 'destroy'])->name('admin_teacher.destroy');

    Route::get('teacher/deleted_list', [TeacherController::class, 'deletedList'])->name('admin_teacher.deletedList');
    Route::post('teacher/restore/{id}', [TeacherController::class, 'restore'])->name('admin_teacher.restore');
    Route::delete('teacher/force-delete/{id}', [TeacherController::class, 'forceDelete'])->name('admin_teacher.forceDelete');

    // class URL routes
    Route::get('class/list', [ClassController::class, 'index'])->name('class.index');
    Route::get('class/add', [ClassController::class, 'create'])->name('class.create');
    Route::post('class/add', [ClassController::class, 'store'])->name('class.store');
    Route::get('class/edit/{id}', [ClassController::class, 'edit'])->name('class.edit');
    Route::put('class/edit/{id}', [ClassController::class, 'update'])->name('class.update');
    Route::delete('class/delete/{id}', [ClassController::class, 'destroy'])->name('class.destroy');

    Route::get('class/deleted_list', [ClassController::class, 'deletedList'])->name('class.deletedList');
    Route::post('class/restore/{id}', [ClassController::class, 'restore'])->name('class.restore');
    Route::delete('class/force-delete/{id}', [ClassController::class, 'forceDelete'])->name('class.forceDelete');

    // subject URL routes
    Route::get('subject/list', [SubjectController::class, 'index'])->name('subject.index');
    Route::get('subject/add', [SubjectController::class, 'create'])->name('subject.create');
    Route::post('subject/add', [SubjectController::class, 'store'])->name('subject.store');
    Route::get('subject/edit/{id}', [SubjectController::class, 'edit'])->name('subject.edit');
    Route::put('subject/edit/{id}', [SubjectController::class, 'update'])->name('subject.update');
    Route::delete('subject/delete/{id}', [SubjectController::class, 'destroy'])->name('subject.destroy');

    Route::get('subject/deleted_list', [SubjectController::class, 'deletedList'])->name('subject.deletedList');
    Route::post('subject/restore/{id}', [SubjectController::class, 'restore'])->name('subject.restore');
    Route::delete('subject/force-delete/{id}', [SubjectController::class, 'forceDelete'])->name('subject.forceDelete');

    //assign_subject URL routes
    Route::get('assign_subject/list', [ClassSubjectController::class, 'index'])->name('assign_subject.index');
    Route::get('assign_subject/add', [ClassSubjectController::class, 'create'])->name('assign_subject.create');
    Route::post('assign_subject/add', [ClassSubjectController::class, 'store'])->name('assign_subject.store');
    Route::get('assign_subject/edit/{id}', [ClassSubjectController::class, 'edit'])->name('assign_subject.edit');
    Route::put('assign_subject/edit/{id}', [ClassSubjectController::class, 'update'])->name('assign_subject.update');
    Route::delete('assign_subject/delete/{id}', [ClassSubjectController::class, 'destroy'])->name('assign_subject.destroy');

    Route::get('assign_subject/deleted_list', [ClassSubjectController::class, 'deletedList'])->name('assign_subject.deletedList');
    Route::post('assign_subject/restore/{id}', [ClassSubjectController::class, 'restore'])->name('assign_subject.restore');
    Route::delete('assign_subject/force-delete/{id}', [ClassSubjectController::class, 'forceDelete'])->name('assign_subject.forceDelete');


    //change password
    Route::get('change_password', [UserController::class, 'change_password']);
    Route::post('change_password', [UserController::class, 'update_change_password']);
});

// ******   Teacher routes   ******//
Route::group(['middleware' => 'teacher'], function () {
    Route::get('teacher/dashboard', [DashboardController::class, 'dashboard']);

    //change password
    Route::get('teacher/change_password', [UserController::class, 'change_password']);
    Route::post('teacher/change_password', [UserController::class, 'update_change_password']);
});

// ******   Student routes   ******//
Route::group(['middleware' => 'student'], function () {
    Route::get('student/dashboard', [DashboardController::class, 'dashboard']);

    //change password
    Route::get('student/change_password', [UserController::class, 'change_password']);
    Route::post('student/change_password', [UserController::class, 'update_change_password']);
});

// ******   Parent routes   ******//
Route::group(['middleware' => 'parent'], function () {
    Route::get('parent/dashboard', [DashboardController::class, 'dashboard']);

    //change password
    Route::get('parent/change_password', [UserController::class, 'change_password']);
    Route::post('parent/change_password', [UserController::class, 'update_change_password']);
});
