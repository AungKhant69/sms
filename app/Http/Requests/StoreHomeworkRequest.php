<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreHomeworkRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'subject_id' => 'required',
            'homework_date' => 'required|date',
            'deadline' => 'required|date|after:homework_date',
            'document_file' => 'required|mimes:pdf',
            'description' => 'required',
        ];
    }
}
