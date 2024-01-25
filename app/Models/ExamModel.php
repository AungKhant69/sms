<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ExamModel extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'exam';
    protected $fillable = ['name', 'created_by', 'updated_by', 'status'];

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
        return $this->hasMany(ExamScheduleModel::class, 'exam_id');
    }
}
