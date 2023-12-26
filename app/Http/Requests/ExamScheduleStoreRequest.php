<?php

namespace App\Http\Requests;

use Carbon\Carbon;
use App\Rules\UniqueRoomSchedule;
use Illuminate\Foundation\Http\FormRequest;

class ExamScheduleStoreRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Adjust the authorization logic if needed
    }

    public function rules()
    {
        return [

            'schedule.*.exam_date' => ['date','after:now'],
            'schedule.*.start_time' => 'date_format:H:i A',
            'schedule.*.end_time' => 'date_format:H:i A|after:schedule.*.start_time',
            'schedule.*.room_number' => [
                'string',
                new UniqueRoomSchedule(
                    $this->input('schedule.*.exam_date'),
                    $this->input('schedule.*.start_time'),
                    $this->input('schedule.*.end_time'),
                    $this->input('schedule.*.room_number')
                ),
            ],

            'schedule.*.full_marks' => 'numeric',
            'schedule.*.passing_marks' => 'numeric',
        ];
    }
}
