<?php

namespace App\Enum;

enum JobPostStatus: string
{
    case CANCELED = 'Canceled';
    case CLOSED = 'Closed';
    case OPENED = 'Opened';
    case STARTED = 'Started';
    case COMPLETED = 'Completed';
}