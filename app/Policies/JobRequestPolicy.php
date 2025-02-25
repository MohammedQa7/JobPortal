<?php

namespace App\Policies;

use App\Enum\JobPostStatus;
use App\Models\JobPost;
use App\Models\JobRequest;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class JobRequestPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, JobRequest $jobRequest): bool
    {
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user, $jobPost): bool
    {
        $job = JobPost::where('slug', $jobPost)
            ->withoutGlobalScopes()
            ->first();

        return ($job->status == JobPostStatus::STARTED->value
            && ($job->user_id == auth()->id() || $job->assigned_user == auth()->id())
            && !$job->isThereNotAccpetedRequests()
        );
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, JobRequest $jobRequest): bool
    {
        return ($user->id == $jobRequest->job->user_id && is_null($jobRequest->is_accepted));
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, JobRequest $jobRequest): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, JobRequest $jobRequest): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, JobRequest $jobRequest): bool
    {
        return false;
    }

}