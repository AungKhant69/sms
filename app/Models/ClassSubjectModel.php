<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClassSubjectModel extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'class_subject';
    protected $fillable = ['class_id', 'subject_id', 'status', 'created_by', 'updated_by'];

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function classData()
    {
        return $this->belongsTo(ClassModel::class, 'class_id');
    }

    public function subjectData()
    {
        return $this->belongsTo(SubjectModel::class, 'subject_id');
    }

}



