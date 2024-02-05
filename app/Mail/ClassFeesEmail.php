<?php

// app/Mail/ClassFeesEmail.php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class ClassFeesEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $student;

    public function __construct(User $student)
    {
        $this->student = $student;
    }

    public function build()
    {
        return $this->view('emails.class_fees')
                    ->subject('Reminder: Class Fees Payment')
                    ->with(['student' => $this->student]);
    }
}

