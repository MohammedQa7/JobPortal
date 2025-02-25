<?php

namespace App\Policies;

use App\Enum\ProposalStatus;
use App\Enum\ProposalTypes;
use App\Models\Project;
use App\Models\Proposal;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Carbon;

class ProposalPolicy
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
    public function view(User $user, Proposal $proposal): bool
    {
        // Example: Only allow the user to view the proposal if they are part of the related project
        if ($proposal->type == ProposalTypes::PROPOSAL->value && $proposal->job_seeker_id == $user->id) {
            return true;
        } else if ($proposal->client_id == $user->id || $proposal->job_seeker_id == $user->id) {
            return true;
        }
        return false;
    }


    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Proposal $proposal): bool
    {
        // return true;
        if ($proposal->type == ProposalTypes::PROPOSAL->value && $proposal->job_seeker_id == $user->id && !$proposal->is_reviewed && $proposal->editing_ends_at >= now()) {
            return true;
        } else if ($proposal->client_id == $user->id && !$proposal->is_reviewed && $proposal->editing_ends_at >= now()) {
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Proposal $proposal): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Proposal $proposal): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Proposal $proposal): bool
    {
        return false;
    }

    public function updateStatus(User $user, Proposal $proposal): bool
    {
        if ($proposal->type == ProposalTypes::OFFER->value) {
            return ($proposal->job_seeker_id == $user->id && $proposal->status == ProposalStatus::PENDING->value);
        }

        return false;
    }
}