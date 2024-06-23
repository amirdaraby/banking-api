<?php

namespace App\Enums;

use App\Traits\EnumToArray;

enum TransactionDirection: string
{
    use EnumToArray;

    case SEND = 'send';

    case RECEIVE = 'receive';
}
