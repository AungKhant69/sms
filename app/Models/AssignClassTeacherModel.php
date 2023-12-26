<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AssignClassTeacherModel extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'assign_class_teacher';
    protected $fillable = ['class_id', 'teacher_id', 'status', 'created_by', 'updated_by'];

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function assignedClasses()
    {
        return $this->belongsTo(ClassModel::class, 'class_id');
    }

    // public function studentData()
    // {
    //     return $this->belongsTo(User::class, 'student_id');
    // }

    public function classData()
    {
        return $this->belongsTo(ClassModel::class, 'class_id');
    }

    public function subjectData()
    {
        return $this->belongsTo(SubjectModel::class, 'subject_id');
    }

    // public function classSubject()
    // {
    //     return $this->belongsTo(ClassSubjectModel::class, 'class_id', 'subject_id');
    // }

    // public function classSubjectTeacher(){
    //     return $this->hasManyThrough(
    //         SubjectModel::class,
    //         ClassSubjectModel::class,
    //         'class_id',
    //         'id'
    //     );
    // }
}
