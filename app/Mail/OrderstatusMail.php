<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderstatusMail extends Mailable
{
    use Queueable, SerializesModels;    
    public $maildata;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($maildata)
    {
        $this->maildata = $maildata;
    }


    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {  
        
        return $this->view('emails.update_order')->with('offer', $this->maildata)
                ->subject($this->maildata['subject']);

    }
}
