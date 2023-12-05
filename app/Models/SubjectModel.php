<?php

namespace App\Models;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubjectModel extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'subject';

    static public function getSingle($id)
    {
        return self::findOrFail($id);
    }

    static public function getRecord(Request $request) //search bar for records
    {
        $record  = SubjectModel::select('subject.*', 'users.name as created_by_name')
                               ->join('users', 'users.id', 'subject.created_by');
        if (!empty($request->get('name'))) {
            $record = $record->where('subject.name', 'like', '%' . $request->get('name') . '%');
        }

        if (!empty($request->get('type'))) {
            $record = $record->where('subject.type', 'like', '%' . $request->get('type') . '%');
        }

        if (!empty($request->get('date'))) {
            $record = $record->whereDate('subject.created_at', '=', $request->get('date'));
        }

        $record =  $record->where('subject.is_delete', '=', '0')
                          ->orderBy('subject.id', 'desc')->paginate(6);
        return $record;
    }

    static public function getSubject(){
        $record  = SubjectModel::select('subject.*')
                               ->join('users', 'users.id', 'subject.created_by')
                               ->where('subject.is_delete', '=', '0')
                               ->where('subject.status', '=', '0')
                               ->orderBy('subject.name', 'asc')->get();
        return $record;
    }
}
