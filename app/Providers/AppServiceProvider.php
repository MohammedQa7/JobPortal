<?php

namespace App\Providers;

use App\Models\JobPost;
use App\Models\Project;
use App\Models\Proposal;
use App\Models\Scopes\JobPostScope;
use App\Models\User;
use App\Policies\ProposalPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;
use Inertia\Inertia;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    protected $policies = [
        Proposal::class => ProposalPolicy::class,
    ];
    public function register(): void
    {
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {

        Gate::define('submit-proposal', function (User $user, JobPost $job) {
            return $user->hasSubmitedProposal($job);
        });

        Gate::define('submit-offer', function (User $user, JobPost $job, $job_seeker) {
            return $user->hasSubmitedOffer($job_seeker, $job);
        });


        Vite::prefetch(concurrency: 3);
    }
}