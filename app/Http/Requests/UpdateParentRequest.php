<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateParentRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $id = $this->route('id');

        $rule = [
            'name' => 'required|string|max:255',
            'gender' => 'required|in:Male,Female',
            'address' => 'required|string',
            'phone_number' => 'nullable|regex:/^[0-9]*$/|min:10|max:15',
            'email' => 'required|email|unique:users,email,'. $id,  //email ka phan mae field ||| a nout ka id ka ignore mae id
            'date_of_birth' => 'required|date|before_or_equal:today',
            'password' => 'nullable|string|min:6|confirmed:password_confirmation',
            'profile_pic' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];

        return $rule;
    }
}
