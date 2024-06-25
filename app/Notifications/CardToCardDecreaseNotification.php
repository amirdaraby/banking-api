<?php

namespace App\Notifications;

use App\Models\Transaction;
use App\Notifications\Channels\SmsChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CardToCardDecreaseNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public Transaction $transaction)
    {
        //
    }


    public function toSms(object $notifiable): object
    {
        return __(
            'card_to_card.sms.decrease', [
                'amount' => $this->transaction->amount,
                'cost_amount' => $this->transaction->transactionCost->amount,
                'done_at' => $this->transaction->updated_at
            ]
        );
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return [SmsChannel::class];
    }

    public function viaQueues(): array
    {
        return [
            SmsChannel::class => 'sms'
        ];
    }
}

