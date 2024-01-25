<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClassModel extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'class';
    protected $fillable = ['name', 'created_by', 'updated_by', 'status', 'fees_amount'];

    public function subjects()
    {
        return $this->hasManyThrough(
            SubjectModel::class,
            ClassSubjectModel::class,
            'class_id',
            'id',
            'id',
            'subject_id'
        );
    }

    // public function students()
    // {
    //     return $this->hasMany(User::class, 'class_id');
    // }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function examSchedules()
    {
        return $this->hasMany(ExamScheduleModel::class, 'class_id');
    }

    // public function subjectData()
    // {
    //     return $this->belongsTo(SubjectModel::class, 'subject_id');
    // }

    // public function teacherClasses()
    // {
    //     return $this->belongsToMany(User::class, 'assign_class_teacher', 'class_id', 'teacher_id')
    //         ->wherePivot('status', 1);
    // }
}
