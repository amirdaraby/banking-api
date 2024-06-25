<?php

namespace App\Sms\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static void send(string $recipient, string $message)
 */
class Sms extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'Sms';
    }
}
