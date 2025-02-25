<?php

namespace App\Http\Requests;

use App\Enum\ProposalTypes;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class ProposalRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'job' => ['required', 'exists:job_posts,id'],
            'coverLetter' => ['required', 'max:4000'],
            'bid' => ['required', 'numeric'],
            'duration' => ['required', 'numeric'],
            'type' => ['required', new Enum(ProposalTypes::class)],
            'jobSeekerID' => ['required_if:type,offer'],
        ];
    }

    public function messages(): array
    {
        return [
            'job.required' => 'Please provide a job in order to create a proposal',
            'job.exists' => 'no recored was found for this job',
            'coverLetter.required' => 'Cover letter is required',
            'coverLetter.max' => 'Cover letter should not exceed 4000 characters',
            'bid.required' => 'Job bid is required',
            'bid.numeric' => 'Job bid should be a valid number ',
            'duration.required' => 'Job duration is required',
            'duration.numeric' => 'Job duration should be a valid number ',
            'jobSeekerID.required_if' => 'Provide the job seeker in order to complete this process'
        ];
    }
}