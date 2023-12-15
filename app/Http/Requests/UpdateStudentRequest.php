<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateStudentRequest extends FormRequest
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
            'admission_date' => 'required|date',
            'email' => 'required|email|unique:users,email,'. $id,  //email ka phan mae field ||| a nout ka id ka ignore mae id
            'admission_number' => 'required|string|unique:users,admission_number,' . $id,
            'class_id' => 'required|integer', // Assuming 'class' is now 'class_id'
            'date_of_birth' => 'required|date|before_or_equal:today',
            'password' => 'nullable|string|min:6|confirmed:password_confirmation',
            'profile_pic' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];

        return $rule;
    }
}
