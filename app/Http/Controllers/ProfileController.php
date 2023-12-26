<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\UpdateSettingsRequest;

class ProfileController extends Controller
{
    public function showSettings()
    {
        return view('admin.profile');
    }

    public function updateSettings(UpdateSettingsRequest $request)
    {
        $user = Auth::user(); // Using Auth facade

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->name = $request->name;
        $user->email = $request->email;
        $user->date_format = $request->date_format;

        $user->save();

        return redirect()->back()->with('success', 'Profile updated.');
    }
}
