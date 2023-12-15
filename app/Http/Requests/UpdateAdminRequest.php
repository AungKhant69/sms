<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAdminRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $id = $this->route('id');

        $rule = [
            'name' => 'required|string|min:3|max:50',
            'email' => 'required|email|unique:users,email,'. $id,  //email ka phan mae field ||| a nout ka id ka ignore mae id
            'password' => 'nullable|string|min:6|confirmed:password_confirmation',
            'profile_pic' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];
        return $rule;
    }
}
