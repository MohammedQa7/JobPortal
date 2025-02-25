<?php

namespace App\Http\Controllers;

use App\Http\Resources\RatingResource;
use App\Models\JobPost;
use App\Models\Rating;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class RatingController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request, $username)
    {
        sleep(1);
        $user = User::where('username', $username)->firstOrFail();
        $ratings = Rating::with([
            'job' => function ($query) {
                $query->withoutGlobalScopes();
            },
            'user'
        ])
            ->where('user_id', '!=', $user->id)
            ->getUserRating($user)
            ->get();
        if ($request->wantsJson()) {
            // Json Response
            return response()->json([
                'ratings' => RatingResource::collection($ratings),
            ]);
        } else {
            // Inertia Router

        }
    }


    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'jobID' => ['required', 'exists:job_posts,id'],
            'rate' => ['required', 'min:1', 'max:5'],
            'description' => ['nullable', 'max:500'],
        ]);

        $job = JobPost::withoutGlobalScopes()
            ->findOrFail($validatedData['jobID']);

        $this->authorize('create', [Rating::class, $job]);

        Rating::create([
            'job_post_id' => $validatedData['jobID'],
            'user_id' => $job->user_id == auth()->id()
            ? $job->assigned_user
            : $job->user_id,
            'rate' => $validatedData['rate'],
            'description' => $validatedData['description'],
        ]);
    }
}