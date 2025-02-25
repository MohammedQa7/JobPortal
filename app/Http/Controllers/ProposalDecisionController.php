<?php

namespace App\Http\Controllers;

use App\Actions\AcceptProposalOffer;
use App\Actions\DeclineProposalOffer;
use App\Models\Proposal;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class ProposalDecisionController extends Controller
{
    use AuthorizesRequests;
    function accept(Proposal $proposal, AcceptProposalOffer $action)
    {
        $this->authorize('updateStatus', $proposal);
        $action->handle($proposal);
        return to_route('proposal.index');
    }


    function decline(Proposal $proposal, DeclineProposalOffer $action)
    {
        $this->authorize('updateStatus', $proposal);
        $action->handle($proposal);

        return to_route('proposal.index');
    }
}