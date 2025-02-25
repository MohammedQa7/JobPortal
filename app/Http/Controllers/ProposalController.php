<?php

namespace App\Http\Controllers;

use App\Enum\GlobalRules;
use App\Enum\JobPostStatus;
use App\Enum\ProposalTypes;
use App\Http\Requests\ProposalRequest;
use App\Http\Requests\ProposalUpdateRequest;
use App\Http\Resources\JobPostResource;
use App\Http\Resources\ProposalResource;
use App\Models\JobPost;
use App\Models\Proposal;
use App\Models\Scopes\JobPostScope;
use App\Services\ProposalService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class ProposalController extends Controller
{

    use AuthorizesRequests;

    public function index()
    {
        $proposals = Proposal::with([
            'job' => function ($query) {
                $query->withoutGlobalScope(JobPostScope::class);
            },
            'jobSeeker'
        ])
            ->where('job_seeker_id', auth()->id())
            ->orWhere('client_id', auth()->id())
            ->get();

        //  getting only the proposals
        $user_proposals = $proposals
            ->where('job_seeker_id', auth()->user()->id)
            ->where('type', ProposalTypes::PROPOSAL->value);

        //  getting only the offers
        $user_offers = $proposals
            ->where('job_seeker_id', auth()->user()->id)
            ->where('type', ProposalTypes::OFFER->value);

        $sent_offers = $proposals
            ->where('client_id', auth()->id())
            ->where('type', ProposalTypes::OFFER->value);


        // Query perfomance issue (5 dublicated queries)
        $on_going_jobs = JobPost::withoutGlobalScopes()
            ->whereNotNull('assigned_user')
            ->where('status', '!=', JobPostStatus::OPENED->value)
            ->where(function ($query) {
                $query->where('assigned_user', auth()->id())
                    ->orWhere('user_id', auth()->id());
            })
            ->get();





        return Inertia::render('Proposal/Proposals', [
            'proposals' => ProposalResource::collection($user_proposals),
            'offers' => ProposalResource::collection($user_offers),
            'sentOffers' => ProposalResource::collection($sent_offers),
            'onGoingJobs' => JobPostResource::collection($on_going_jobs),
            'proposalsCount' => $user_proposals->count(),
            'offersCount' => $user_offers->count(),
            'sentOffersCount' => $sent_offers->count(),
            'onGoingJobsCount' => $on_going_jobs->count(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(JobPost $job)
    {
        // Check if the user can submit a proposal
        if (Gate::allows('submit-proposal', $job)) {
            return Inertia::render('Error/SubmitedProposal403');
        }

        $proposalTypes = ProposalTypes::toCollection();


        return Inertia::render('Proposal/CreateProposal', [
            'job' => new JobPostResource($job),
            'proposalTypes' => $proposalTypes,
            'tax' => (GlobalRules::TAX_PERCENTAGE->value / 100),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProposalRequest $request)
    {
        $data = [
            'uuid' => Str::uuid(),
            'job_post_id' => $request->job,
            'job_seeker_id' => auth()->user()->id,
            'cover_letter' => $request->coverLetter,
            'bid' => $request->bid,
            'duration' => $request->duration,
            'editing_ends_at' => now()->addHours(GlobalRules::ALLOWED_EDITING_TIME->value),
        ];

        try {
            DB::beginTransaction();
            $proposalService = new ProposalService($data);
            $job = JobPost::where('id', $data['job_post_id'])->firstOrFail();


            if ($request->type == ProposalTypes::OFFER->value) {
                Gate::allows('submit-offer', [$job, $request->jobSeekerID])
                    ? Inertia::render('Error/SubmitedProposal403')
                    : $proposal = $proposalService->createOffer($request);

            } else {

                Gate::allows('submit-proposal', $job)
                    ? Inertia::render('Error/SubmitedProposal403')
                    : $proposal = $proposalService->createProposal();
            }

            session()->flash('success', true);
            DB::commit();
            return to_route('proposal.show', ['proposal' => $proposal]);
        } catch (\Throwable $th) {
            DB::rollBack();
            throw ValidationException::withMessages(['error' => 'something went wrong']);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Proposal $proposal)
    {
        $this->authorize('view', $proposal);

        $proposalTypes = ProposalTypes::toCollection();
        $proposal->load([
            'job' =>
            function ($query) {
                $query->withoutGlobalScope(JobPostScope::class);
            },
            'jobSeeker',
            'client'
        ]);



        return Inertia::render('Proposal/EditProposal', [
            'proposal' => new ProposalResource($proposal),
            'proposalTypes' => $proposalTypes,
            'successSession' => session()->get('success'),
            'tax' => (GlobalRules::TAX_PERCENTAGE->value / 100),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProposalUpdateRequest $request, Proposal $proposal)
    {
        $this->authorize('update', $proposal);
        $proposal->update([
            'cover_letter' => $request->coverLetter,
            'bid' => $request->bid,
            'duration' => $request->duration,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Proposal $proposal)
    {
        //
    }



}