<?php

namespace App\Http\Controllers;

use App\Enum\GlobalRules;
use App\Http\Requests\ProposalRequest;
use App\Http\Resources\ProjectResource;
use App\Http\Resources\ProposalResource;
use App\Models\Project;
use App\Models\Proposal;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class ProposalController extends Controller
{

    use AuthorizesRequests;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user_proposals = Proposal::with('project', 'user')
            ->where('user_id', auth()->user()->id)
            ->get();


        return Inertia::render('Proposal/Proposals', [
            'proposals' => ProposalResource::collection($user_proposals),
            'proposalsCount' => count($user_proposals->toArray()),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Project $job)
    {
        if (Gate::allows('submit-proposal', $job)) {
            return Inertia::render('Error/SubmitedProposal403');
        }
        return Inertia::render('Proposal/CreateProposal', [
            'job' => new ProjectResource($job),
            'tax' => (GlobalRules::TAX_PERCENTAGE->value / 100),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProposalRequest $request)
    {

        $proposal = Proposal::create([
            'project_id' => $request->project,
            'user_id' => auth()->user()->id,
            'cover_letter' => $request->coverLetter,
            'bid' => $request->bid,
            'duration' => $request->duration,
            'editing_ends_at' => now()->addHours(GlobalRules::ALLOWED_EDITING_TIME->value),
        ]);

        return to_route('job.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Proposal $proposal)
    {
        $proposal->load('project', 'user');
        return Inertia::render('Proposal/EditProposal', [
            'proposal' => new ProposalResource($proposal),
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
    public function update(ProposalRequest $request, Proposal $proposal)
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


    public function updateIsReviewedProposal(Proposal $proposal, Request $request)
    {
        !$proposal->is_reviewed && is_bool($request->isReviewed)
            ? $proposal->update([
                'is_reviewed' => $request->isReviewed
            ])
            : '';
    }
}