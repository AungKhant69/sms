<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\Request;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
// use Illuminate\Http\Request;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'class_id',
        'parent_id',
        'teacher_id',
        'date_format',
        'student_id',
        'admission_number',
        'admission_date',
        'date_of_birth',
        'gender',
        'address',
        'phone_number',
        'user_type',
        'profile_pic',
        'created_by',
        'updated_by',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    protected $table = 'users';

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function parent()
    {
        return $this->belongsTo(User::class, 'parent_id');
    }

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    // public function myStudent()
    // {
    //     return $this->hasMany(User::class, 'class_id');
    // }

    public function assignClassTeacher()
    {
        return $this->hasOne(AssignClassTeacherModel::class, 'teacher_id');
    }

    public function classData()
    {
        return $this->belongsTo(ClassModel::class, 'class_id');
    }

    // public function assignedClasses()
    // {
    //     return $this->belongsToMany(ClassModel::class, 'assign_class_teacher', 'teacher_id', 'class_id');
    // }

    static public function getEmailSingle($email)
    { //becoz we need to use inside controller
        return self::where('email', '=', $email)->first();
    }

    static public function getTokenSingle($remember_token)
    { //becoz we need to use inside controller
        return self::where('remember_token', '=', $remember_token)->first();
    }


}
