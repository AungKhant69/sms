<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class ClassSubjectModel extends Model
{
    use HasFactory;
    protected $table = 'class_subject';

    static public function getSingle($id){
        return self::find($id);
    }

    static public function getRecord(Request $request)
    {
        $record = self::select('class_subject.*', 'class.name as class_name', 'subject.name as subject_name', 'users.name as created_by_name')
            ->join('subject', 'subject.id', '=', 'class_subject.subject_id')
            ->join('class', 'class.id', '=', 'class_subject.class_id')
            ->join('users', 'users.id', '=', 'class_subject.created_by')
            ->where('class_subject.is_delete', '=', 0);

        if (!empty($request->get('class_name'))) {
            $record = $record->where('class.name', 'like', '%' . $request->get('class_name') . '%');
        }

        if (!empty($request->get('subject_name'))) {
            $record = $record->where('subject.name', 'like', '%' . $request->get('subject_name') . '%');
        }

        if (!empty($request->get('date'))) {
            $record = $record->whereDate('class_subject.created_at', '=', $request->get('date'));
        }

        $record = $record->orderBy('class_subject.id', 'desc')
            ->paginate(10);
        return $record;
    }

    static public function getAlreadyFirst($class_id, $subject_id)
    {
        return self::where('class_id', '=', $class_id)->where('subject_id', '=', $subject_id)->first();
    }

    static public function getAssignSubjectID($class_id)
    {
        return self::where('class_id', '=', $class_id)->where('is_delete', '=', 0)->get();
    }

    static public function deleteSubject($class_id)
    {
        return self::where('class_id', '=', $class_id)->delete();
    }


}
