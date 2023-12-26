<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConfigModel extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'paginate', 'status', 'created_by', 'updated_by'];


}
