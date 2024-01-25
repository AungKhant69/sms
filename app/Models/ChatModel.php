<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ChatModel extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'chat';
    protected $fillable = [
        'sender_id',
        'receiver_id',
        'message',
        'file',
        'status',

    ];

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    public function getConnectUser()
    {
        return $this->belongsTo(User::class, 'connect_user_id');
    }

    static public function CountMessage($connect_user_id, $user_id)
    {
        return self::where('sender_id', '=', $connect_user_id)
                    ->where('receiver_id', '=', $user_id)
                    ->where('status', '=', 0)->count();
    }

    static public function updateCount($sender_id, $receiver_id)
    {
        self::where('sender_id', '=', $receiver_id)
                    ->where('receiver_id', '=', $sender_id)
                    ->where('status', '=', 0)->update(['status' => '1']);
    }

    public function getFile()
    {
        if(!empty($this->file) && file_exists('uploads/chat/' . $this->file))
        {
            return url('uploads/chat/' . $this->file);
        }
        else
        {
            return "";
        }
    }

    static public function getAllChatUserCount()
    {
        $user_id = Auth::user()->id;
        $return  = self::select('chat.id')
                ->join('users as sender', 'sender.id', '=', 'chat.sender_id')
                ->join('users as receiver', 'receiver.id', '=', 'chat.receiver_id')
                ->where('chat.receiver_id', '=', $user_id)
                ->where('chat.status', '=', 0)
                ->count();

        return $return;
    }
}
