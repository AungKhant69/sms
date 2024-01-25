<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\BusinessEmailModel;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public $data = [];
    public function __construct()
    {
        $this->data = [
            'header_title' => 'Business Email',
            'getRecord' => [],
        ];
    }

    public function businessEmail()
    {
        $data['getRecord'] = BusinessEmailModel::getSingle();
        return view('admin.setting')->with([
            'data' => $this->data,
        ]);
    }

    public function updateBusinessEmail(Request $request)
    {
        $setting = BusinessEmailModel::getSingle();

        if (!$setting) {
            // If no record is found, create a new one
            $setting = new BusinessEmailModel();
        }

        $setting->stripe_email = $request->stripe_email;
        $setting->stripe_key = $request->stripe_key;
        $setting->stripe_secret = $request->stripe_secret;

        $setting->save();

        return redirect()->back()->with('success', 'Business Email has been updated');
    }
}
