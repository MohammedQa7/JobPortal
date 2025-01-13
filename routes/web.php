<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ProposalController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('home', function () {
    return Inertia::render('Home');
})->name('home');


// Jobs
Route::resource('jobs', ProjectController::class)->names('job');
Route::get('jobs/details/{job}', [ProjectController::class, 'index'])->name('job.index.modal');

// ----
Route::middleware('auth')->group(function () {
    // Proposals
    Route::resource('proposal', ProposalController::class)->except('create')->names('proposal');
    Route::put('proposal/{proposal}/update', [ProposalController::class, 'updateIsReviewedProposal'])->name('proposal.review.update');
    Route::get('proposal/{job}/apply', [ProposalController::class, 'create'])->name('proposal.create');

    // Users Jobs
    Route::get('my/jobs', [ProjectController::class, 'userJobs'])->name('user.jobs');
    Route::get('my/jobs/{job}', [ProjectController::class, 'userJobs'])->name('user.job.show');
    Route::get('my/jobs/{job}/proposals', [ProjectController::class, 'jobProposals'])->name('job.proposals');

});



Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';