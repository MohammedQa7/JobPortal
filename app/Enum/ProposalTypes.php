<?php

namespace App\Enum;

enum ProposalTypes: string
{
    case PROPOSAL = 'proposal';
    case OFFER = 'offer';


    public static function toCollection()
    {
        return collect(ProposalTypes::cases())->mapWithKeys(function ($type) {
            return [
                $type->value => $type->value,
            ];
        });
    }
}