<?php

namespace App\Enum;

enum EscrowStatus: string
{
    case PENDING = 'pending';
    case RESERVED = 'reserved';
}