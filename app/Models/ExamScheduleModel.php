<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ExamScheduleModel extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'exam_schedule';
    protected $fillable = [
        'exam_id',
        'class_id',
        'subject_id',
        'exam_date',
        'start_time',
        'end_time',
        'room_number',
        'full_marks',
        'passing_marks',
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


}
