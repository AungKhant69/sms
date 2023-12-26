<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Models\ExamScheduleModel;

class UniqueRoomSchedule implements Rule
{
    protected $examDate;
    protected $startTime;
    protected $endTime;
    protected $roomNumber;

    public function __construct($examDate, $startTime, $endTime, $roomNumber)
    {
        $this->examDate = $examDate;
        $this->startTime = $startTime;
        $this->endTime = $endTime;
        $this->roomNumber = $roomNumber;
    }

    public function passes($attribute, $value)
    {
        return !ExamScheduleModel::where([
            ['room_number', '=', $this->roomNumber],
            ['exam_date', '=', $this->examDate],
        ])->where(function ($query) {
            $query->whereBetween('start_time', [$this->startTime, $this->endTime])
                  ->orWhereBetween('end_time', [$this->startTime, $this->endTime]);
        })->exists();
    }

    public function message()
    {
        return 'The room is already scheduled for another exam during this time.';
    }
}
