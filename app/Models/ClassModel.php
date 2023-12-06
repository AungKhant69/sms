<?php

namespace App\Models;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClassModel extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'class';

    static public function getSingle($id)
    {
        return self::findOrFail($id);
    }

    static public function getClass(){
        $record  = ClassModel::select('class.*')
                               ->join('users', 'users.id', 'class.created_by')
                               ->whereNull('class.deleted_at')
                               ->where('class.status', '=', '1')
                               ->orderBy('class.name', 'asc')->get();
        return $record;
    }
}
