<?php

namespace App\Http\Controllers;

use App\Actions\AcceptJobRequest;
use App\Actions\DeclineJobRequest;
use App\Enum\JobPostStatus;
use App\Enum\ProposalStatus;
use App\Models\JobRequest;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;


class HandleJobRequestsController extends Controller
{
    use AuthorizesRequests;
    function accept(AcceptJobRequest $action, Request $request)
    {
        $validatedData = $request->validate([
            'jobRequestID' => ['required', 'exists:job_requests,id'],
        ]);

        $job_request = JobRequest::with([
            'job' => function ($query) {
                $query->withoutGlobalScopes();
            },
            'job.proposals' => function ($query) {
                $query->where('is_accepted', true)
                    ->where('status', ProposalStatus::ACCEPTED->value);
            },
        ])
            ->findOrFail($validatedData['jobRequestID']);

        $this->authorize('update', $job_request);
        $action->handle($job_request);

        return to_route('proposal.index');
    }

    function decline(DeclineJobRequest $action, Request $request)
    {
        $validatedData = $request->validate([
            'jobRequestID' => ['required', 'exists:job_requests,id'],
        ]);

        $job_request = JobRequest::with([
            'job' => function ($query) {
                $query->withoutGlobalScopes();
            },
            'user'
        ])
            ->findOrFail($validatedData['jobRequestID']);

        $this->authorize('update', $job_request);
        $action->handle($job_request);

    }
}