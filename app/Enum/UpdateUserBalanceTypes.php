<?php

namespace App\Enum;

enum UpdateUserBalanceTypes: string
{
    case INCREMENT = 'increment';
    case DECREMENT = 'decrement';
}