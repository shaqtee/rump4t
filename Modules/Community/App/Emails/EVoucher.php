<?php

namespace Modules\Community\App\Emails;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class EVoucher extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    protected $datasEmail;

    public function __construct($datasEmail)
    {
        $this->datasEmail = $datasEmail;
    }

    /**
     * Build the message.
     */
    public function build(): self
    {
        return $this->view('community::Emails.eVoucherMail')
                ->with([
                    'datasEmail' => $this->datasEmail,
                ])
                ->subject('E-Vouchers');
    }
}