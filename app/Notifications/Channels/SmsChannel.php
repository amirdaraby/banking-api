<?php

namespace App\Notifications\Channels;

use App\Models\User;
use App\Notifications\CardToCardIncreaseNotification;
use App\Sms\Facades\Sms;

class SmsChannel
{
    public function send(User $notifiable, CardToCardIncreaseNotification $notification): void
    {
        Sms::send($notifiable->routeNotificationForSms(), $notification->toSms());
    }
}
