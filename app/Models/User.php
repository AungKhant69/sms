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


    static public function getStudent(Request $request)
    {
        $data =  self::select('users.*')->where('users.user_type', '=', 3)
            ->where('users.is_delete', '=', 0);

        $data = $data->orderBy('users.id', 'desc')->paginate(10);
        return $data;
    }

    static public function getEmailSingle($email)
    { //becoz we need to use inside controller
        return self::where('email', '=', $email)->first();
    }

    static public function getTokenSingle($remember_token)
    { //becoz we need to use inside controller
        return self::where('remember_token', '=', $remember_token)->first();
    }
}
