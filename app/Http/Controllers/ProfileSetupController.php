<?php

namespace App\Http\Controllers;

use App\Http\Requests\StepOneRequest;
use App\Models\Specialty;
use App\Services\SetupStepsService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProfileSetupController extends Controller
{

    function create()
    {
        $specialities = Specialty::pluck('name', 'id');
        return Inertia::render('Profile/Setup', [
            'specialities' => $specialities,
        ]);
    }

    function store(Request $request)
    {
        $request->validate([
            'jobTitle' => ['required', 'string', 'min:6', 'max:255'],
            'bio' => ['nullable', 'string', 'max:2000'],
            'speciality' => ['required', 'exists:specialties,id'],
        ]);

        auth()->user()->update([
            'job_title' => $request->jobTitle,
            'bio' => $request->bio,
            'speciality_id' => $request->speciality,
        ]);

    }

    function validateStepData(Request $request)
    {
        match ($request->stepIndex) {
            1 => $request->validate([
                'jobTitle' => ['required', 'string', 'min:6', 'max:255'],
                'bio' => ['nullable', 'string', 'max:2000'],
            ]),
            2 => $request->validate([
                'speciality' => ['required', 'exists:specialties,slug'],
            ]),
            default => abort(404, 'invalid step')
        };
    }
}