<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\ExamController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ClassController;
use App\Http\Controllers\ParentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\HomeworkController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\ClassSubjectController;
use App\Http\Controllers\FeesCollectionController;
use App\Http\Controllers\AssignClassTeacherController;

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

Route::get('forgot-password', [AuthController::class, 'forgotpassword'])->name('forgotpassword');
Route::post('forgot-password', [AuthController::class, 'postForgotPassword'])->name('postForgotPassword');

Route::get('reset/{token}', [AuthController::class, 'reset'])->name('reset');
Route::post('reset/{token}', [AuthController::class, 'postReset'])->name('postReset');

// ******   Admin routes   ******//

Route::group(['middleware' => 'admin', 'prefix' => 'admin'], function () {
    Route::get('dashboard', [DashboardController::class, 'dashboard']);
    Route::get('setting', [UserController::class, 'businessEmail'])->name('admin.setting');
    Route::post('setting', [UserController::class, 'updateBusinessEmail'])->name('admin.setting');

    Route::get('admin/list', [AdminController::class, 'index'])->name('admin.index');
    Route::get('admin/add', [AdminController::class, 'create'])->name('admin.create');
    Route::post('admin/add', [AdminController::class, 'store'])->name('admin.store');
    Route::get('admin/edit/{id}', [AdminController::class, 'edit'])->name('admin.edit');
    Route::put('admin/edit/{id}', [AdminController::class, 'update'])->name('admin.update');
    Route::delete('admin/delete/{id}', [AdminController::class, 'destroy'])->name('admin.destroy');

    Route::get('/profile', [ProfileController::class, 'showSettings'])->name('admin_settings.index');
    Route::patch('/profile', [ProfileController::class, 'updateSettings'])->name('admin_settings.update');

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

    //assign_class_teacher URL routes
    Route::get('assign_class_teacher/list', [AssignClassTeacherController::class, 'index'])->name('assign_class_teacher.index');
    Route::get('assign_class_teacher/add', [AssignClassTeacherController::class, 'create'])->name('assign_class_teacher.create');
    Route::post('assign_class_teacher/add', [AssignClassTeacherController::class, 'store'])->name('assign_class_teacher.store');
    Route::get('assign_class_teacher/edit/{id}', [AssignClassTeacherController::class, 'edit'])->name('assign_class_teacher.edit');
    Route::put('assign_class_teacher/edit/{id}', [AssignClassTeacherController::class, 'update'])->name('assign_class_teacher.update');
    Route::delete('assign_class_teacher/delete/{id}', [AssignClassTeacherController::class, 'destroy'])->name('assign_class_teacher.destroy');

    Route::get('assign_class_teacher/deleted_list', [AssignClassTeacherController::class, 'deletedList'])->name('assign_class_teacher.deletedList');
    Route::post('assign_class_teacher/restore/{id}', [AssignClassTeacherController::class, 'restore'])->name('assign_class_teacher.restore');
    Route::delete('assign_class_teacher/force-delete/{id}', [AssignClassTeacherController::class, 'forceDelete'])->name('assign_class_teacher.forceDelete');

    // Examinations URL routes
    Route::get('examinations/exam/list', [ExamController::class, 'index'])->name('exam.index');
    Route::get('examinations/exam/add', [ExamController::class, 'create'])->name('exam.create');
    Route::post('examinations/exam/add', [ExamController::class, 'store'])->name('exam.store');
    Route::get('examinations/exam/edit/{id}', [ExamController::class, 'edit'])->name('exam.edit');
    Route::put('examinations/exam/edit/{id}', [ExamController::class, 'update'])->name('exam.update');
    Route::delete('examinations/exam/delete/{id}', [ExamController::class, 'destroy'])->name('exam.destroy');

    Route::get('examinations/exam/deleted_list', [ExamController::class, 'deletedList'])->name('exam.deletedList');
    Route::post('examinations/exam/restore/{id}', [ExamController::class, 'restore'])->name('exam.restore');
    Route::delete('examinations/exam/force-delete/{id}', [ExamController::class, 'forceDelete'])->name('exam.forceDelete');

    Route::get('examinations/exam_schedule', [ExamController::class, 'exam_schedule'])->name('exam_schedule.index');
    Route::post('examinations/exam_schedule_store', [ExamController::class, 'exam_schedule_store'])->name('exam_schedule.store');

    Route::get('examinations/marks_register', [ExamController::class, 'marks_register'])->name('exam_marks.index');
    Route::post('examinations/submit_marks_register', [ExamController::class, 'store_marks_register'])->name('exam_marks.store');

    //  Attendance/Homework routes
    Route::get('student_management/attendance', [AttendanceController::class, 'StudentAttendance'])->name('attendance.index');
    Route::post('student_management/submit_attendance', [AttendanceController::class, 'store_attendance'])->name('attendance.store');
    Route::get('student_management/attendance_report', [AttendanceController::class, 'AttendanceReport'])->name('attendance_report.index');

    Route::get('student_management/homework', [HomeworkController::class, 'index'])->name('homework.index');
    Route::get('student_management/homework/add', [HomeworkController::class, 'create'])->name('homework.create');
    Route::post('student_management/homework/add', [HomeworkController::class, 'store'])->name('homework.store');
    Route::get('student_management/homework/edit/{id}', [HomeworkController::class, 'edit'])->name('homework.edit');
    Route::put('student_management/homework/edit/{id}', [HomeworkController::class, 'update'])->name('homework.update');
    Route::delete('student_management/homework/delete/{id}', [HomeworkController::class, 'destroy'])->name('homework.destroy');

    Route::get('student_management/homework/deleted_list', [HomeworkController::class, 'deletedList'])->name('homework.deletedList');
    Route::post('student_management/restore/{id}', [HomeworkController::class, 'Restore'])->name('homework.restore');
    Route::delete('student_management/force-delete/{id}', [HomeworkController::class, 'ForceDelete'])->name('homework.forceDelete');

    // Fees Collection Routes
    Route::get('fees_collection/collect_fees', [FeesCollectionController::class, 'collect_fees'])->name('fees_collection.index');
    Route::get('fees_collection/add_fees/{student_id}', [FeesCollectionController::class, 'add_fees'])->name('fees_collection.create');
    Route::post('fees_collection/add_fees/{student_id}', [FeesCollectionController::class, 'store_fees'])->name('fees_collection.store');

    Route::get('stripe/payment-error', [FeesCollectionController::class, 'payment_error'])->name('stripe.payment_error');
    Route::get('stripe/payment-success', [FeesCollectionController::class, 'payment_success'])->name('stripe.payment_success');

});

// ******   Teacher routes   ******//
Route::group(['middleware' => 'teacher', 'prefix' => 'teacher'], function () {
    Route::get('dashboard', [DashboardController::class, 'dashboard'])->name('teacher.dashboard');
    Route::get('admin/list', [AdminController::class, 'index_teacher_side'])->name('admin_teacher_side.index');
    Route::get('parent/list', [ParentController::class, 'index_teacher_side'])->name('parent_teacher_side.index');

    Route::get('my_class_subject', [AssignClassTeacherController::class, 'myClassSubject'])->name('teacher.my_class_subject');
    Route::get('my_student', [StudentController::class, 'myStudent'])->name('teacher.myStudent');
    Route::get('my_exam_timetable', [ExamController::class, 'teacher_exam_timetable'])->name('teacher.my_exam_timetable');

    Route::get('/profile', [TeacherController::class, 'showSettings'])->name('teacher_settings.index');
    Route::patch('/profile', [TeacherController::class, 'updateSettings'])->name('teacher_settings.update');

    Route::get('marks_register', [ExamController::class, 'marks_register_teacher'])->name('exam_marks_teacher.index');
    Route::post('submit_marks_register', [ExamController::class, 'store_marks_register_teacher'])->name('exam_marks_teacher.store');

    Route::get('student_management/attendance', [AttendanceController::class, 'StudentAttendance_teacher'])->name('attendance_teacher.index');
    Route::post('student_management/submit_attendance', [AttendanceController::class, 'store_attendance_teacher'])->name('attendance_teacher.store');
    Route::get('student_management/attendance_report', [AttendanceController::class, 'AttendanceReport_teacher'])->name('attendance_report_teacher.index');

    Route::get('student_management/homework', [HomeworkController::class, 'index_teacher_side'])->name('homework_teacher.index');
    Route::get('student_management/homework/add', [HomeworkController::class, 'create_teacher_side'])->name('homework_teacher.create');
    Route::post('student_management/homework/add', [HomeworkController::class, 'store_teacher_side'])->name('homework_teacher.store');
    Route::get('student_management/homework/edit/{id}', [HomeworkController::class, 'edit_teacher_side'])->name('homework_teacher.edit');
    Route::put('student_management/homework/edit/{id}', [HomeworkController::class, 'update_teacher_side'])->name('homework_teacher.update');
    Route::delete('student_management/homework/delete/{id}', [HomeworkController::class, 'destroy_teacher_side'])->name('homework_teacher.destroy');

    Route::get('student_management/homework/deleted_list', [HomeworkController::class, 'deletedList_teacher_side'])->name('homework_teacher.deletedList');
    Route::post('student_management/restore/{id}', [HomeworkController::class, 'Restore_teacher_side'])->name('homework_teacher.restore');
    Route::delete('student_management/force-delete/{id}', [HomeworkController::class, 'ForceDelete_teacher_side'])->name('homework_teacher.forceDelete');

});

// ******   Student routes   ******//
Route::group(['middleware' => 'student', 'prefix' => 'student'], function () {
    Route::get('dashboard', [DashboardController::class, 'dashboard'])->name('student.dashboard');
    Route::get('admin/list', [AdminController::class, 'index_student_side'])->name('admin_student_side.index');
    Route::get('teacher/list', [TeacherController::class, 'index_student_side'])->name('teacher_student_side.index');

    Route::get('my_subject', [SubjectController::class, 'studentSubject'])->name('student.mySubject');

    Route::get('my_exam_timetable', [ExamController::class, 'student_exam_timetable'])->name('student.my_exam_timetable');

    Route::get('my_academic_info/attendance_report', [AttendanceController::class, 'AttendanceReport_student'])->name('attendance_report_student.index');
    Route::get('my_academic_info/my_homework', [HomeworkController::class, 'index_student_side'])->name('homework_student.index');

    Route::get('/profile', [StudentController::class, 'showSettings'])->name('student_settings.index');
    Route::patch('/profile', [StudentController::class, 'updateSettings'])->name('student_settings.update');

});

// ******   Parent routes   ******//
Route::group(['middleware' => 'parent', 'prefix' => 'parent'], function () {
    Route::get('dashboard', [DashboardController::class, 'dashboard'])->name('parent.dashboard');
    Route::get('admin/list', [AdminController::class, 'index_parent_side'])->name('admin_parent_side.index');
    Route::get('teacher/list', [TeacherController::class, 'index_parent_side'])->name('teacher_parent_side.index');

    Route::get('my_student', [ParentController::class, 'myStudentParent'])->name('parent.my_student');
    Route::get('my_student/subject/{student_id}', [SubjectController::class, 'ParentStudentSubject'])->name('parent.ParentStudentSubject');
    Route::get('my_student/exam_timetable/{student_id}', [ExamController::class, 'parent_exam_timetable'])->name('parent.exam_timetable');
    Route::get('my_student/homework/{student_id}', [HomeworkController::class, 'homework_parent_side'])->name('parent.checkHomework');

    Route::get('fees_collection/add_fees/{student_id}', [FeesCollectionController::class, 'add_fees_parent_side'])->name('fees_collection_parent.create');
    Route::post('fees_collection/add_fees/{student_id}', [FeesCollectionController::class, 'store_fees_parent_side'])->name('fees_collection_parent.store');
    Route::get('stripe/payment-error', [FeesCollectionController::class, 'payment_error'])->name('stripe.payment_error_parent_side');
    Route::get('stripe/payment-success', [FeesCollectionController::class, 'payment_success'])->name('stripe.payment_success_parent_side');

    Route::get('/profile', [ParentController::class, 'showSettings'])->name('parent_settings.index');
    Route::patch('/profile', [ParentController::class, 'updateSettings'])->name('parent_settings.update');
});

// ******   Common routes   ******//
Route::group(['middleware' => 'common'], function () {
    Route::get('chat', [ChatController::class, 'chat'])->name('chat.index');
    Route::post('submit_message', [ChatController::class, 'submit_message'])->name('chat.submit_message');
    Route::post('getChatBox', [ChatController::class, 'getChatBox'])->name('chat.getChatBox');
    Route::post('getSearchedUser', [ChatController::class, 'getSearchedUser'])->name('chat.getSearchedUser');

});
