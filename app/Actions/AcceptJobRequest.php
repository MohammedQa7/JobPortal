<?php

namespace App\Actions;

use App\Enum\JobPostStatus;
use App\Enum\JobRequestsTypes;
use App\Enum\ProposalStatus;
use App\Events\TransferEscrowMoney;
use App\Models\Escrow;
use App\Models\JobPost;
use App\Models\Proposal;
use App\Models\User;
use Illuminate\Support\Facades\DB;


class AcceptJobRequest
{
    public function handle($job_request)
    {
        match ($job_request->type) {
            JobRequestsTypes::SUBMIT->value => $this->acceptSumbittingJob($job_request),
            JobRequestsTypes::CANCEL->value => $this->acceptCanclingJob($job_request),
        };

    }



    protected function acceptSumbittingJob($job_request)
    {
        try {
            DB::beginTransaction();

            $job_request->job->update([
                'status' => JobPostStatus::COMPLETED->value,
            ]);

            $this->MarkJobRequestAsAccepted($job_request);
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    protected function acceptCanclingJob($job_request)
    {
        try {
            DB::beginTransaction();
            $job_request->job->update([
                'status' => JobPostStatus::CANCELED->value,
            ]);

            $this->MarkJobRequestAsAccepted($job_request);
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }


    protected function MarkJobRequestAsAccepted($job_request)
    {
        $job_request->update([
            'is_accepted' => true,
        ]);

        $job_accepted_propoasl = $job_request->job->proposals->first();
        event(new TransferEscrowMoney($job_accepted_propoasl));
    }
}