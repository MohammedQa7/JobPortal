<?php

namespace App\Http\Controllers;

use App\Actions\SubmitCancelJobRequest;
use App\Actions\SubmitJobCompletionRequest;
use App\Events\MakeJobCompleted;
use App\Http\Requests\JobRequestValidation;
use App\Models\JobRequest;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class JobRequestsController extends Controller
{
    use AuthorizesRequests;
    function submit(JobRequestValidation $request, SubmitJobCompletionRequest $action)
    {
        $this->authorize('create', [JobRequest::class, $request->job]);
        $action->handle($request->validated());
        return to_route('proposal.index');
    }


    function cancel(JobRequestValidation $request, SubmitCancelJobRequest $action)
    {
        $this->authorize('create', [JobRequest::class, $request->job]);
        $action->handle($request->validated());

        return to_route('proposal.index');
    }

}