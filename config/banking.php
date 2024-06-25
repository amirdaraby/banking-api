<?php

return [
    'card_to_card' => [
        'transaction_cost' => (int)env('BANKING_TRANSACTION_COST', 5000),
        'min_amount' => (int)env('BANKING_CARD_TO_CARD_MIN_AMOUNT', 10_000),
        'max_amount' => (int)env('BANKING_CARD_TO_CARD_MAX_AMOUNT', 500_000_000),
    ],
];
