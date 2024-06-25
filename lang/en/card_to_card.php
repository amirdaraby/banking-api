<?php

return [
    'card_not_found' => 'card: :number not found, please create card first.',
    'insufficient_balance' => 'Your balance is not enough,your balance: :your needed balance: :needed',
    'failed' => 'Transaction failed, please try again later.',
    'succeeded' => 'Transaction succeeded.',

    'sms' => [
        'increase' => "Bank\n card-to-card transfer\n amount: - :amount\n cost_amount: :cost_amount\n done at: :done_at",
        'decrease' => "Bank\n card-to-card deposit\n amount: + :amount\n cost_amount: :cost_amount\n done at: :done_at",
    ],
];
