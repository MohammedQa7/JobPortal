<?php

namespace App\Actions;

use App\Enum\JobPostStatus;
use App\Events\MakeJobCompleted;
use App\Events\TransferEscrowMoney;
use App\Models\JobPost;
use App\Models\JobRequest;
use App\Models\Proposal;
use App\Models\Rating;
use App\Models\User;
use Illuminate\Support\Facades\DB;


class SubmitJobCompletionRequest
{
    public function handle($request)
    {
        $job = JobPost::where('slug', $request['job'])
            ->withoutGlobalScopes()
            ->first();
        $proposal = Proposal::where('uuid', $request['proposal'])->first();


        try {
            DB::beginTransaction();
            if ($job->user_id == auth()->id()) {
                JobRequest::create([
                    'job_post_id' => $job->id,
                    'user_id' => auth()->id(),
                    'is_accepted' => true,
                ]);
                $this->MarkJobAsCompleted($job, $proposal);
            } else {
                JobRequest::create([
                    'job_post_id' => $job->id,
                    'user_id' => auth()->id(),
                ]);
            }

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }


    public function MarkJobAsCompleted($job, $proposal)
    {
        $job->update([
            'status' => JobPostStatus::COMPLETED->value,
        ]);

        event(new TransferEscrowMoney($proposal));
    }

}