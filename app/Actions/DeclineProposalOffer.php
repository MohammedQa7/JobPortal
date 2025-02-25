<?php

namespace App\Actions;

use App\Enum\ProposalStatus;
use App\Models\Proposal;


class DeclineProposalOffer
{
    public function handle(Proposal $proposal)
    {
        $proposal->update([
            'status' => ProposalStatus::DECLINED->value,
        ]);
    }
}