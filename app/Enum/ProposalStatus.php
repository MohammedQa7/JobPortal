<?php

namespace App\Enum;

enum ProposalStatus: string
{
    case CANCELED = 'Canceled';
    case PENDING = 'Pending';
    case DECLINED = 'Declined';
    case INSUFFICIENT_FUNDS = 'insufficient funds';
    case ACCEPTED = 'Accepted';
}