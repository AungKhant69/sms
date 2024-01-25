<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubjectModel extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'subject';
    protected $fillable = ['name', 'type', 'status', 'created_by', 'updated_by'];

    public function classData()
    {
        return $this->belongsTo(ClassModel::class, 'class_id');
    }

    public function classSubjects()
    {
        return $this->hasMany(ClassSubjectModel::class, 'subject_id');
    }

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
        return $this->hasMany(ExamScheduleModel::class, 'subject_id');
    }
}



