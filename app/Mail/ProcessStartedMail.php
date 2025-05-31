<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ProcessStartedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $userName;
    public $projectName;

    public function __construct($userName, $projectName)
    {
        $this->userName = $userName;
        $this->projectName = $projectName;
    }

    public function build()
    {
        return $this->subject('Your process has finished')
                    ->view('emails.process_started');
    }
}
