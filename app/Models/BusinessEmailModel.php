<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BusinessEmailModel extends Model
{
    use HasFactory;
    protected $table = 'business_email';
    protected $fillable = [
        'stripe_email',
        'stripe_key',
        'stripe_secret',
    ];

    static public function getSingle()
    {
        return self::find(1);
    }
}
