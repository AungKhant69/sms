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

    static public function getRecord(Request $request) //search bar for records
    {
        $record  = ClassModel::select('class.*', 'users.name as created_by_name')
                               ->join('users', 'users.id', 'class.created_by');
        if (!empty($request->get('name'))) {
            $record = $record->where('class.name', 'like', '%' . $request->get('name') . '%');
        }

        if (!empty($request->get('date'))) {
            $record = $record->whereDate('class.created_at', '=', $request->get('date'));
        }

        $record =  $record->whereNull('class.deleted_at')
                          ->where('class.status', '=', '1')
                          ->orderBy('class.id', 'desc')->paginate(10);
        return $record;
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
