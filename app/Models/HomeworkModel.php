<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class HomeworkModel extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'homework';
    protected $fillable = [
        'subject_id',
        'homework_date',
        'deadline',
        'document_file',
        'description',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'homework_date' => 'datetime',
        'deadline' => 'datetime',
    ];

    public function subjectData()
    {
        return $this->belongsTo(SubjectModel::class, 'subject_id');
    }

    public function classData()
    {
        return $this->belongsTo(ClassModel::class, 'class_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function classSubject()
    {
        return $this->belongsTo(ClassSubjectModel::class, 'subject_id');
    }

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function getDocument()
    {
        if (!empty($this->document_file) && file_exists('uploads/homework/' . $this->document_file)) {
            return url('uploads/homework/' . $this->document_file);
        }
        else
        {
            return null;
        }

    }
}
