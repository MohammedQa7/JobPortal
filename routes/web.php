<?php

use App\Http\Controllers\DownloadingAttachments;
use App\Http\Controllers\HandleJobRequestsController;
use App\Http\Controllers\isReviewedController;
use App\Http\Controllers\JobPostController;
use App\Http\Controllers\JobRequestsController;
use App\Http\Controllers\MobileVerificationController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProfileSetupController;
use App\Http\Controllers\ProposalController;
use App\Http\Controllers\ProposalDecisionController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\UploadTemporatyAttachmentsController;
use App\Http\Controllers\UserBalanceController;
use App\Http\Middleware\CheckProfileSetup;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Inertia\Inertia;
use Stripe\Checkout\Session;
use Stripe\PaymentIntent;
use Stripe\Stripe;
use Twilio\Rest\Client;

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
Route::resource('jobs', JobPostController::class)->names('job');
Route::get('jobs/details/{job}', [JobPostController::class, 'index'])->name('job.index.modal'); // a modal for showing a single job

Route::middleware('auth')->group(function () {

    // Proposals
    Route::resource('proposal', ProposalController::class)->except('create')->names('proposal');
    Route::prefix('proposal')->group(function () {
        Route::get('/{job}/apply', [ProposalController::class, 'create'])->name('proposal.create');
        Route::put('/{proposal}/update', isReviewedController::class)->name('proposal.review.update');
        Route::put('/{proposal}/accept', [ProposalDecisionController::class, 'accept'])->name('proposal.accept');
        Route::put('/{proposal}/decline', [ProposalDecisionController::class, 'decline'])->name('proposal.decline');
    });
    // -----

    // User Jobs
    Route::get('my/jobs', [JobPostController::class, 'userJobs'])->name('user.jobs');
    Route::get('my/jobs/{job}', [JobPostController::class, 'userJobs'])->name('user.job.show');
    Route::get('my/jobs/{job}/proposals', [JobPostController::class, 'jobProposals'])->name('job.proposals');


    // Started Job
    Route::get('job/started/{job}', [JobPostController::class, 'startedJob'])->name('job.started.show');

    // Job Requests (Submit And Cancel) reqeusts from both seeker and client
    Route::post('sumbit/completion/job', [JobRequestsController::class, 'submit'])->name('submit.job.request');
    Route::post('cancel/job', [JobRequestsController::class, 'cancel'])->name('cancel.job.request');

    // Job Requests (Accept And Decline) -> requests from seeker 
    Route::put('accpet/job/request', [HandleJobRequestsController::class, 'accept'])->name('accept.job.request');
    Route::put('decline/job/request', [HandleJobRequestsController::class, 'decline'])->name('decline.job.request');

    // User Rating.
    Route::get('/{username}/rating', [RatingController::class, 'index'])->name('rate.index');
    Route::post('rate/store', [RatingController::class, 'store'])->name('rate.store');


    // profile setup
    Route::middleware([
        CheckProfileSetup::class,
    ])->group(function () {
        Route::get('profile/setup', [ProfileSetupController::class, 'create'])->name('profile.setup.create');
        Route::post('profile/setup/store', [ProfileSetupController::class, 'store'])->name('profile.setup.store');
        Route::post('step/check', [ProfileSetupController::class, 'validateStepData'])->name('validate-step-data');
    });

    // Phone number verification
    Route::prefix('verify')->group(function () {
        Route::get('/', [MobileVerificationController::class, 'create'])->name('mobile.verification.notice');
        Route::get('/show', [MobileVerificationController::class, 'show'])->name('mobile.verification.show');
        Route::post('/send', [MobileVerificationController::class, 'send'])->name('mobile.verification.send');
        Route::post('/phone', [MobileVerificationController::class, 'verify'])->name('mobile.verification.verify');
    });



    // Payment
    Route::post('/funds/checkout', PaymentController::class)->name('checkout');
    Route::get('success', UserBalanceController::class)->name('success');
    Route::get('cancel', function () {
        return to_route('job.index');
    })->name('cancel');


    // Uploading Attachments
    Route::post('/attachments/upload', [UploadTemporatyAttachmentsController::class, 'uploadAttachments'])->name('upload');
    Route::post('/attachments/revert', [UploadTemporatyAttachmentsController::class, 'revertAttachments'])->name('revert');

    // Downloading files.
    Route::get('/attachment/download', DownloadingAttachments::class)->name('download');
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