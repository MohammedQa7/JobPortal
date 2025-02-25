<?php

namespace App\Enum;

enum StartedJobsStatus: string
{
    case IN_PROGRESS = 'in_progress';
    case COMPLETED = 'completed';
    case CANCLED = 'canceld';


    public static function toCollection()
    {
        return collect(StartedJobsStatus::cases())->mapWithKeys(function ($type) {
            return [
                $type->value => $type->value,
            ];
        });
    }
}