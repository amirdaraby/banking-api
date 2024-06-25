<?php

namespace App\Sms\Services;

use App\Sms\SmsServiceInterface;
use Ghasedak\GhasedakApi;

class Ghasedak implements SmsServiceInterface
{

    private GhasedakApi $api;

    public function __construct()
    {
        $this->api = new GhasedakApi(config('sms.providers.ghasedak.api_key'));
    }

    public function send(string $recipient, string $message): void
    {
        $this->api->SendSimple($recipient, $message);
    }
}
