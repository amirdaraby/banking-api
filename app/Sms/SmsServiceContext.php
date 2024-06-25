<?php

namespace App\Sms;

use App\Sms\Services\Ghasedak;
use App\Sms\Services\Kavenegar;
use InvalidArgumentException;

class SmsServiceContext
{
    private SmsServiceInterface $provider;

    public function __construct(string $smsProvider)
    {
        $this->provider = match ($smsProvider) {
            'ghasedak' => new Ghasedak(),
            'kavenegar' => new Kavenegar(),
            default => throw new InvalidArgumentException('Unknown smsProvider: ' . $smsProvider),
        };
    }

    public function send(string $recipient, string $message)
    {
        $this->provider->send($recipient, $message);
    }
}
