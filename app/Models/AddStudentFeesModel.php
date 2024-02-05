<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AddStudentFeesModel extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'add_student_fees';
    protected $fillable = [
        'student_id',
        'class_id',
        'total_amount',
        'paid_amount',
        'remaining_amount',
        'payment_type',
        'payment_id',
        'message',
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

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
