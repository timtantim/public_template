<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VerifyCode extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
	public function __construct($content)
	{
		$this->content = $content;
	}

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('youremail@gmail.com')
                    ->subject('Verify Code')
                    ->markdown('emails.verify_code')
                    ->with('content',$this->content);
    }
}
