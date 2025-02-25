<?php

namespace App\Actions;

use App\Enum\JobPostStatus;
use App\Enum\JobRequestsTypes;
use App\Events\TransferEscrowMoney;
use App\Models\JobPost;
use App\Models\JobRequest;
use App\Models\Proposal;
use Illuminate\Support\Facades\DB;


class SubmitCancelJobRequest
{
    public function handle($request)
    {
        $job = JobPost::where('slug', $request['job'])
            ->withoutGlobalScopes()
            ->first();
        $proposal = Proposal::where('uuid', $request['proposal'])->first();

        DB::beginTransaction();
        try {
            if ($job->user_id == auth()->id()) {
                JobRequest::create([
                    'job_post_id' => $job->id,
                    'user_id' => auth()->id(),
                    'reason_message' => $request['message'] ?? null,
                    'type' => JobRequestsTypes::CANCEL->value,
                    'is_accepted' => true,
                ]);
                $this->MarkJobAsCanceled($job, $proposal);
            } else {
                JobRequest::create([
                    'job_post_id' => $job->id,
                    'user_id' => auth()->id(),
                    'reason_message' => $request['message'] ?? null,
                    'type' => JobRequestsTypes::CANCEL->value,
                ]);
            }

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    public function MarkJobAsCanceled($job, $proposal)
    {
        $job->update([
            'status' => JobPostStatus::CANCELED->value,
        ]);

        // Return the escrow money to the client.
        event(new TransferEscrowMoney($proposal));
    }
}