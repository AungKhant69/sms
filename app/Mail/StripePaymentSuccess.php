<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\AddStudentFeesModel;

class StripePaymentSuccess extends Mailable
{
    use Queueable, SerializesModels;

    public $payment;

    public function __construct(AddStudentFeesModel $payment)
    {
        $this->payment = $payment;
    }

    public function build()
    {
        return $this->view('emails.stripe_payment_success')
                    ->subject('Stripe Payment Success')
                    ->with(['payment' => $this->payment]);
    }
}

