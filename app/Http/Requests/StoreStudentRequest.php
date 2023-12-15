<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreStudentRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'gender' => 'required|in:Male,Female',
            'admission_date' => 'required|date',
            'email' => 'required|email|unique:users',
            'admission_number' => 'required|string|unique:users',
            'class_id' => 'required|integer', // Assuming 'class' is now 'class_id'
            'date_of_birth' => 'required|date|before_or_equal:today',
            'password' => 'required|string|min:6|confirmed:password_confirmation',
            'profile_pic' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];
    }
}
