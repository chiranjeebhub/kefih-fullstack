<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class EmailMessage extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
		public $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
       return $this->view('emails.email',['data'=>$this->data])
                    ->from($this->data['from'], $this->data['from_name'])
                    // ->cc($this->data['from'], $this->data['from_name'])
                    // ->bcc($this->data['from'], $this->data['from_name'])
                    // ->replyTo($this->data['from'], $this->data['from_name'])
                    ->subject($this->data['subject']);
    }
}
