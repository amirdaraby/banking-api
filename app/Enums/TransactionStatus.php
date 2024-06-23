<?php

namespace App\Enums;

use App\Traits\EnumToArray;

enum TransactionStatus: string
{
    use EnumToArray;

    case INIT = 'init';
    case SUCCESS = 'success';
    case FAILED = 'failed';
}
