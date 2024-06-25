<?php

namespace App\Sms;

interface SmsServiceInterface
{
    public function send(string $recipient, string $message):void;
}
