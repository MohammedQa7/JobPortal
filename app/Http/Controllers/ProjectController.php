<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\ProjectResource;
use App\Http\Resources\ProposalResource;
use App\Models\Project;
use App\Models\Proposal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;

class ProjectController extends Controller
{


    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, Project $job = null)
    {
        $jobs = Project::with('user')->paginate(10);

        $single_job = $job ? new ProjectResource($job->load('user')) : null;
        $single_job ? $single_job->all_skills = true : '';
        return Inertia::render('Jobs', [
            'jobs' => Inertia::merge(fn() => ProjectResource::collection($jobs)->toArray(request())),
            'singleJob' => $single_job,
            'isDrawerOpend' => $job ? true : false,
            'jobPagination' => $jobs->toArray(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Project $job, Request $request)
    {

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Project $project)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Project $project)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project)
    {
        //
    }


    // --------------- Non-resource Functions

    public function userJobs(Project $job = null)
    {
        $user_jobs = Project::where('user_id', auth()->user()->id)->select('title', 'slug', 'created_at')->paginate(20);
        $single_job = $job ? new ProjectResource($job->load('user')) : null;
        $single_job ? $single_job->all_skills = true : '';
        return Inertia::render('Jobs/UserJobs', [
            'jobs' => ProjectResource::collection($user_jobs),
            'singleJob' => $single_job,
            'isDrawerOpend' => $job ? true : false,
            'jobsCount' => $user_jobs->total(),
        ]);
    }

    public function jobProposals(Project $job)
    {
        $proposals = Proposal::where('project_id', $job->id)
            ->with('user.rating')
            ->get();

        return Inertia::render('Jobs/JobProposals', [
            'jobProposals' => ProposalResource::collection($proposals),
            'jobProposalsCount' => count($proposals->toArray()),
        ]);
    }

}