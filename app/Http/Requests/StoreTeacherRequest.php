<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTeacherRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'gender' => 'required|in:Male,Female',
            'address' => 'required|string',
            'phone_number' => 'nullable|regex:/^[0-9]*$/|min:10|max:15',
            'email' => 'required|email|unique:users',
            'date_of_birth' => 'required|date|before_or_equal:today',
            'password' => 'required|string|min:6|confirmed:password_confirmation',
            'profile_pic' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];
    }
}
