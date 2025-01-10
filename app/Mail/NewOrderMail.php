<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewOrderMail extends Mailable
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
        
        return $this->view('emails.new_order')->with('offer', $this->maildata)
                ->subject($this->maildata['subject'])
                ->attachData($this->maildata['attachment'], 'invoice.pdf', [
                    'mime' => 'application/pdf',
                ]);

        // $email = $this->markdown('emails.new_order') 
        //     ->subject($this->maildata['subject']); 
        // if(!empty($this->maildata['attachment'])){    
        //     $email->attach($this->maildata['attachment']);
        // }

        // return $email;

    }
}
