<?php

namespace App\Http\Controllers;

use App\Actions\UpdateUserBalance;
use App\Enum\JobPostStatus;
use App\Enum\ProposalTypes;
use App\Helpers\GlobalHelper;
use App\Http\Requests\JobPostRequest;
use App\Http\Resources\JobPostResource;
use App\Http\Resources\JobRequestResource;
use App\Http\Resources\ProposalResource;
use App\Models\JobPost;
use App\Models\JobRequest;
use App\Models\Proposal;
use App\Models\Rating;
use App\Services\CategoryService;
use App\Traits\hasAttachments;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class JobPostController extends Controller implements HasMiddleware
{
    use hasAttachments, AuthorizesRequests;
    protected $categoryService;

    public static function middleware(): array
    {
        return [
            new Middleware(
                'auth',
                only: ['create', 'store', 'update', 'edit', ' destroy']
            ),
        ];
    }


    function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }



    public function index(Request $request, JobPost $job = null)
    {

        $jobs = JobPost::search($request->search)
            ->with('user', 'category', 'attachments')
            ->FilterByCategory($request)
            ->withCount('proposals')
            ->paginate(10)
            ->withQueryString();

        $job
            ? $job->all_skills = true
            : null;


        return Inertia::render('Jobs', [
            'jobs' => Inertia::merge(fn() => JobPostResource::collection($jobs)->toArray(request())),
            'jobPagination' => $jobs->toArray(),
            'singleJob' => $job
            ? new JobPostResource($job->load('user.rating', 'attachments'))
            : null,

            'isDrawerOpend' => $job ? true : false,
            'categories' => $this->categoryService->getCategories(),
            'searchAndFilter' => [
                'search' => $request->search,
                'category' => $request->category
                ? explode(' ', $request->category)
                : [],
            ]
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Jobs/CreateJob', [
            'categories' => $this->categoryService->getCategories(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(JobPostRequest $request, UpdateUserBalance $action)
    {

        // $this->authorize('create', [JobPost::class]);

        try {
            DB::beginTransaction();
            $job = JobPost::create([
                'user_id' => auth()->id(),
                'title' => $request->title,
                'slug' => GlobalHelper::slug_ar($request->title),
                'description' => $request->description ?? null,
                'budget' => $request->budget,
                'duration' => $request->duration,
                'status' => JobPostStatus::OPENED->value,
                'skills' => implode(',', $request->skills),
                'category_id' => $request->selectedCategory
            ]);


            // Update User Balance
            // $action->handle(UpdateUserBalanceTypes::DECREMENT->value, $request->budget);

            // move the uploaded attachments into the presisted file path and remove them from temp file 
            if ($request->attachments) {
                foreach ($request->attachments as $attachment) {
                    $new_path = $this->moveToNewPath($attachment);
                    $job->attachments()->create([
                        'attachment_path' => $new_path,
                    ]);
                }
            }

            DB::commit();
            return to_route('job.index');
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }

    }

    /**
     * Display the specified resource.
     */
    public function show(JobPost $job, Request $request)
    {

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(jobPost $job)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, jobPost $job)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(jobPost $job)
    {
        //
    }


    // --------------- Non-resource Functions

    public function userJobs(JobPost $job = null)
    {
        $user_jobs = JobPost::where('user_id', auth()->user()->id)
            ->select('title', 'slug', 'created_at')
            ->latest()
            ->paginate(20);

        $single_job = $job
            ? new JobPostResource($job->load('user.rating', 'attachments'))
            : null;

        $single_job
            ? $single_job->all_skills = true
            : '';


        return Inertia::render('Jobs/UserJobs', [
            'jobs' => JobPostResource::collection($user_jobs),
            'singleJob' => $single_job,
            'isDrawerOpend' => $job ? true : false,
            'jobsCount' => $user_jobs->total(),
        ]);
    }

    public function jobProposals(JobPost $job, Request $request)
    {
        $proposals = Proposal::where('job_post_id', $job->id)
            ->with('jobSeeker.rating', 'job')
            ->SearchByType($request->type)
            ->get();

        $proposalTypes = ProposalTypes::toCollection();

        return Inertia::render('Jobs/JobProposals', [
            'job' => $job,
            'jobProposals' => Inertia::defer(fn() => ProposalResource::collection($proposals)),
            'jobProposalsCount' => count($proposals->toArray()),
            'selectedTabView' => $request->type ?? null,
            'proposalTypes' => $proposalTypes,
        ]);
    }


    public function startedJob($job)
    {
        $job = JobPost::withoutGlobalScopes()
            ->with([
                'proposals' => function ($query) {
                    return $query->where('is_accepted', true);
                },
                'user.rating'
            ])
            ->whereHas('proposals', function ($query) {
                return $query->where('is_accepted', true);
            })
            ->where('slug', $job)
            ->where('status', '!=', JobPostStatus::OPENED->value)
            ->firstOrFail();

        $requests = JobRequest::with('user')
            ->where('job_post_id', $job->id)
            ->where('is_accepted', null)
            ->whereNot('user_id', auth()->id())->get();

        $has_user_rated_job = auth()->user()->hasRatedJob($job);
        $can_user_rate_job = auth()->user()->canRateJob($job);


        return Inertia::render('Jobs/StartedJob', [
            'job' => new JobPostResource($job),
            'userReqeusts' => JobRequestResource::collection($requests),
            'canSendRequest' => !$job->isThereNotAccpetedRequests(),
            'isJobCanceled' => $job->status == JobPostStatus::CANCELED->value
            ? true
            : false,
            'hasUserRatedJob' => $has_user_rated_job,
            'canUserRateJob' => $can_user_rate_job,
            'deadline' => now()->addDays((int) $job->proposals->first()->duration)
                ->format('d/m/Y'),
            'deadlineForHumans' => now()->addDays((int) $job->proposals->first()->duration)
                ->diffForHumans(),
        ]);
    }
}