<?php

namespace App\Actions;

use App\Enum\JobPostStatus;
use App\Enum\ProposalStatus;
use App\Models\Escrow;
use App\Models\JobPost;
use App\Models\Proposal;
use App\Models\User;
use Illuminate\Support\Facades\DB;


class AcceptProposalOffer
{
    public function handle(Proposal $proposal)
    {
        $proposal->load('client');
        try {
            DB::beginTransaction();

            $proposal->update([
                'is_accepted' => true,
                'status' => $proposal->bid > $proposal->client->balance
                ? ProposalStatus::INSUFFICIENT_FUNDS->value
                : ProposalStatus::ACCEPTED->value,
                'deadline' => $proposal->status == ProposalStatus::ACCEPTED->value
                ? now()->addDays((int) $proposal->duration)
                : null
            ]);


            if ($proposal->status == ProposalStatus::ACCEPTED->value) {
                $this->createEscrow($proposal);
                $this->updateJobPost($proposal);
            }
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }


    protected function createEscrow($proposal)
    {
        $escrow = Escrow::create([
            'proposal_id' => $proposal->id,
            'client_id' => $proposal->client_id,
            'job_seeker_id' => $proposal->job_seeker_id,
            'amount' => $proposal->bid,
            'offer_accepted_at' => $proposal->created_at,
        ]);

        $client = User::where('id', $proposal->client_id)->firstOrFail();


        $escrow
            ? $client->update([
                'balance' => $client->balance - $escrow->amount,
            ])
            : '';
    }


    protected function updateJobPost($proposal)
    {
        $job_post = JobPost::where('id', $proposal->job_post_id)->firstOrFail();
        $job_post->update([
            'assigned_user' => $proposal->job_seeker_id,
            'status' => JobPostStatus::STARTED->value,
        ]);
    }
}