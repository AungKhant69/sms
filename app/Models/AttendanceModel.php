<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AttendanceModel extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'attendance';
    protected $fillable = [
        'class_id',
        'attendance_date',
        'student_id',
        'attendance_type',
        'created_by',
        'updated_by',
    ];

    public function classData()
    {
        return $this->belongsTo(ClassModel::class, 'class_id');
    }

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }
}
