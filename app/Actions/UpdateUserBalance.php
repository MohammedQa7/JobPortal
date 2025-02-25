<?php

namespace App\Actions;

use App\Enum\ProposalStatus;
use App\Enum\UpdateUserBalanceTypes;
use App\Models\Proposal;

class UpdateUserBalance
{
    public AcceptProposalOffer $action;
    function __construct(AcceptProposalOffer $action)
    {
        $this->action = $action;
    }
    public function handle($update_type, $balance)
    {

        match ($update_type) {
            UpdateUserBalanceTypes::INCREMENT->value => auth()->user()->increment('balance', $balance),
            UpdateUserBalanceTypes::DECREMENT->value => auth()->user()->decrement('balance', $balance),
        };


        $this->checkForHeldProposals();
    }


    function checkForHeldProposals()
    {
        $proposals = Proposal::where('client_id', auth()->id())
            ->where('status', ProposalStatus::INSUFFICIENT_FUNDS->value)
            ->latest()
            ->get();

        if ($proposals) {
            foreach ($proposals as $proposal) {
                $this->action->handle($proposal);
            }
        }
    }
}