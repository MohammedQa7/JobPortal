<?php

namespace App\Enum;

enum JobRequestsTypes: string
{
    case SUBMIT = 'submit';
    case CANCEL = 'cancel';


    public static function toCollection()
    {
        return collect(JobRequestsTypes::cases())->mapWithKeys(function ($type) {
            return [
                $type->value => $type->value,
            ];
        });
    }
}