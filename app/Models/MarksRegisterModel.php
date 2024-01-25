<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MarksRegisterModel extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'marks_register';
    protected $fillable = [
        'student_id',
        'exam_id',
        'class_id',
        'subject_id',
        'exam_marks',
        'homework_marks',
        'created_by',
        'updated_by',
    ];

    public function classData()
    {
        return $this->belongsTo(ClassModel::class, 'class_id');
    }

    public function subjectData()
    {
        return $this->belongsTo(SubjectModel::class, 'subject_id');
    }

    public function exam()
    {
        return $this->belongsTo(ExamModel::class, 'exam_id');
    }

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }
}
