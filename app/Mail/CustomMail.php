<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CustomMail extends Mailable
{
    use Queueable, SerializesModels;

    private $template;
    public $subject;
    public $bcc = array();
    public $cc = array();
    public $replyTo = array();
    public $viewParam;

    /**
     * Create a new message instance.
     *
     * @param string $template
     * @param $viewParam
     * @param string $subject
     * @param array $bcc
     * @param array $cc
     */
    public function __construct($template, $viewParam, $subject, $bcc = [], $cc = [])
    {
        $this->template = $template;
        $this->viewParam = $viewParam;
        $this->subject = $subject;
        $this->bcc = $bcc;
        $this->cc = $cc;
    }


    /**
     * @param $address
     * @param null $name
     */
    public function addBcc($address, $name = null) {
        parent::bcc($address, $name);
    }

    /**
     * @param $address
     * @param null $name
     */
    public function addCc($address, $name = null) {
        parent::cc($address, $name);
    }

    /**
     * @param array|object|string $address
     * @param null $name
     * @return Mailable|void
     */
    public function replyTo($address, $name = null) {
        parent::replyTo($address, $name);
    }

    /**
     * Build the message.
     *
     */
    public function build(): self
    {
        $mail = $this->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'))
            ->subject($this->subject)
            ->view($this->template, ['email' => $this->viewParam]);

        return $mail;
    }
}
