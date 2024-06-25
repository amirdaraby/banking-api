<?php

namespace App\Listeners;

use App\Events\CardToCardSuccessEvent;
use App\Notifications\CardToCardDecreaseNotification;
use App\Notifications\CardToCardIncreaseNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendCardToCardNotification
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(CardToCardSuccessEvent $event): void
    {
        $event
            ->sentTransaction
            ->card->account->user->notify(new CardToCardDecreaseNotification($event->sentTransaction));

        $event
            ->receivedTransaction
            ->card->account->user->notify(new CardToCardIncreaseNotification($event->receivedTransaction));
    }
}
