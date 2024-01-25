<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\ChatModel;
use App\Helper\FormHelper;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Mail\Events\MessageSent;

class ChatController extends Controller
{
    public $pagination = '';
    public $data = [];
    public function __construct()
    {
        $config = FormHelper::getConfig();
        $this->pagination = $config['paginate'];
        $this->data = [
            'header_title' => 'My Chat',
            'getRecord' => [],
        ];
    }

    public function chat(Request $request)
    {
        $sender_id = Auth::user()->id;
        if (!empty($request->receiver_id)) {
            $receiver_id = base64_decode($request->receiver_id);
            if ($receiver_id == $sender_id) {
                return redirect()->back()->with('error', 'You cannot send messages to yourself.');
                exit();
            }
            ChatModel::updateCount($sender_id, $receiver_id);
            $this->data['receiver_id'] = $receiver_id;
            $this->data['getReceiver'] = User::findOrFail($receiver_id);
            $this->data['getChatData'] = $this->getChatData($receiver_id, $sender_id);
            // dd($this->data['getChatData']);

        } else {
            $this->data['receiver_id'] = '';
        }
        $this->data['getChatUser'] = $this->getChatUser($sender_id);
        // dd($this->data['getChatUser']);

        return view('chat.list')->with([
            'data' => $this->data,
        ]);
    }

    // public function submit_message(Request $request)
    // {
    //     try {
    //         $filename = null;

    //         if (!empty($request->file('file_name'))) {
    //             $ext = $request->file('file_name')->getClientOriginalExtension();
    //             $file = $request->file('file_name');
    //             $randomStr = date('Ymshis') . Str::random(20);
    //             $filename = strtolower($randomStr) . '.' . $ext;
    //             $file->move('uploads/chat', $filename);
    //         }

    //         $chat = ChatModel::create([
    //             'sender_id' => Auth::user()->id,
    //             'receiver_id' => $request->receiver_id,
    //             'message' => $request->message,
    //             'file' => $filename,
    //         ]);

    //         // Broadcasting the event to others
    //         broadcast(new NewMessageEvent($chat))->toOthers();

    //         return response()->json([
    //             'status' => true,
    //             'success' => [
    //                 'getChat' => $chat,
    //                 'formattedTime' => $chat->created_at->diffForHumans(),
    //             ],
    //         ], 200);
    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'status' => false,
    //             'error' => $e->getMessage(),
    //         ], 500);
    //     }
    // }

    public function submit_message(Request $request)
    {
        // dd($request->all());
        // $chat = new ChatModel;
        if (!empty($request->file('file_name'))) {
            $ext = $request->file('file_name')->getClientOriginalExtension();
            $file = $request->file('file_name');
            $randomStr = date('Ymshis') . Str::random(20);
            $filename = strtolower($randomStr) . '.' . $ext;
            $file->move('uploads/chat', $filename);
        } else {
            $filename = null;
        }
        $chat = ChatModel::create([
            'sender_id' => Auth::user()->id,
            'receiver_id' => $request->receiver_id,
            'message' => $request->message,
            'file' => $filename,

        ]);

        return response()->json([
            'status' => true,
            'success' => [
                'getChat' => $chat,
                'formattedTime' => $chat->created_at->diffForHumans(),
            ],
        ], 200);
    }


    private function getChatData($receiver_id, $sender_id)
    {
        $query = ChatModel::where(function ($e) use ($receiver_id, $sender_id) {

            $e->where(function ($e) use ($receiver_id, $sender_id) {
                $e->where('receiver_id', $sender_id)
                    ->where('sender_id', $receiver_id)
                    ->where('status', '>', '-1');
            })->orWhere(function ($e) use ($receiver_id, $sender_id) {
                $e->where('receiver_id', $receiver_id)
                    ->where('sender_id', $sender_id)
                    ->where('status', '>', '-1');
            });
        })
            ->where('message', '!=', '')
            ->with('sender', 'receiver')
            ->orderBy('id', 'asc')->get();

        return $query;
    }

    private static function getChatUser($user_id)
    {
        $getChatUser = ChatModel::select("chat.*", DB::raw('CASE WHEN chat.sender_id = "' . $user_id . '"
                   THEN chat.receiver_id ELSE chat.sender_id END AS connect_user_id'))
            ->join('users as sender', 'sender.id', '=', 'chat.sender_id')
            ->join('users as receiver', 'receiver.id', '=', 'chat.receiver_id')
            ->whereIn('chat.id', function ($query) use ($user_id) {

                $query->selectRaw('max(chat.id)')->from('chat')
                    ->where('chat.status', '<', 2)
                    ->where(function ($query) use ($user_id) {
                        $query->where('chat.sender_id', '=', $user_id)
                            ->orWhere(function ($query) use ($user_id) {
                                $query->where('chat.receiver_id', '=', $user_id)
                                    ->where('chat.status', '>', -1);
                            });
                    })
                    ->groupBy(DB::raw('CASE WHEN chat.sender_id = "' . $user_id . '"
                                  THEN chat.receiver_id ELSE chat.sender_id END'));
            })
            ->orderBy('chat.id', 'desc')
            ->get();

        $result = array();
        foreach ($getChatUser as $value) {
            $data = array();
            $data['id'] = $value->id;
            $data['message'] = $value->message;
            $data['created_at'] = $value->created_at;
            $data['user_id'] = $value->connect_user_id;
            $data['is_online'] = $value->getConnectUser->OnlineUser();
            $data['name'] = $value->getConnectUser->name;
            // $data['profile_pic'] = FormHelper::getProfile($value->getConnectUser->profile_pic);
            $data['profile_pic'] = $value->getConnectUser->getProfileDirect();
            // dd($value->getConnectUser->profile_pic);
            $data['messageCount'] = $value->CountMessage($value->connect_user_id, $user_id);
            $result[] = $data;
        }
        return $result;
    }

    public function getChatBox(Request $request)
    {

        $receiver_id = $request->receiver_id;
        $sender_id = Auth::user()->id;

        ChatModel::updateCount($sender_id, $receiver_id);

        $getReceiver = User::findOrFail($receiver_id);
        $getChatData = $this->getChatData($receiver_id, $sender_id);

        return response()->json([
            'status' => true,
            'receiver_id' => base64_encode($receiver_id),
            'success' => [
                'getReceiver' => $getReceiver,
                'getChatData' => $getChatData,
            ],
        ], 200);
    }

    public function getSearchedUser(Request $request)
    {
        $receiver_id = $request->receiver_id;
        $sender_id = Auth::user()->id;
        $getChatUser = $this->getChatUser($sender_id);

        return response()->json([
            'status' => true,
            'success' => [
                'getChatUser' => $getChatUser,
                'receiver_id' => $receiver_id,
            ],
        ], 200);
    }
}
