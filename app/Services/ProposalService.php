<?php

namespace App\Services;

use App\Actions\CreateEscrowTransaction;
use App\Enum\ProposalTypes;
use App\Models\JobPost;
use App\Models\Proposal;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;


class ProposalService
{

    protected $proposal_data = null;
    function __construct($data = null)
    {
        $this->proposal_data = $data;
    }

    public function createProposal()
    {
        return Proposal::create($this->proposal_data);
    }

    public function createOffer($request)
    {
        $this->proposal_data['client_id'] = auth()->user()->id;
        $this->proposal_data['job_seeker_id'] = $request->jobSeekerID;
        $this->proposal_data['type'] = ProposalTypes::OFFER->value;
        $this->updateInvitedOnCreate($this->proposal_data);

        $proposal = Proposal::create($this->proposal_data);

        return $proposal;
    }


    public function updateInvitedOnCreate($data)
    {
        $update_seeker_proposal = Proposal::where('job_post_id', $data['job_post_id'])
            ->where('job_seeker_id', $data['job_seeker_id'])->first();

        $update_seeker_proposal->update([
            'invited' => true,
        ]);
    }

}