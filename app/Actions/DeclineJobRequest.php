<?php

namespace App\Actions;

use App\Enum\JobPostStatus;
use App\Enum\ProposalStatus;
use App\Models\Escrow;
use App\Models\JobPost;
use App\Models\Proposal;
use App\Models\User;
use Illuminate\Support\Facades\DB;


class DeclineJobRequest
{
    public function handle($job_request)
    {
        $job_request->update([
            'is_accepted' => false,
        ]);
    }

}