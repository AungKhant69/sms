<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\Request;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
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

    public function amount()
    {
        return $this->belongsTo(AddStudentFeesModel::class, 'class_id');
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

    public function subjectData()
    {
        return $this->belongsTo(SubjectModel::class, 'subject_id');
    }

    public function classTeachers()
    {
        return $this->hasMany(AssignClassTeacherModel::class, 'class_id', 'class_id');
    }

    static public function getEmailSingle($email)
    { //becoz we need to use inside controller
        return self::where('email', '=', $email)->first();
    }

    static public function getTokenSingle($remember_token)
    { //becoz we need to use inside controller
        return self::where('remember_token', '=', $remember_token)->first();
    }

    static public function getPaidAmount($student_id, $class_id)
    {
        return AddStudentFeesModel::where('student_id', $student_id)
            ->where('class_id', $class_id)
            ->sum('add_student_fees.paid_amount');
    }

    public function getProfileDirect()
    {
        $profilePicPath = 'public/uploads/' . $this->profile_pic;

        if (!empty($this->profile_pic) && Storage::exists($profilePicPath)) {
            return Storage::url($profilePicPath);
        } else {
            return asset('dist/img/default-avatar-profile-icon-grey-photo-placeholder-vector-17317730.jpg');
        }
    }

     public function OnlineUser()
    {
        return Cache::has('OnlineUser' . $this->id);
    }
}
