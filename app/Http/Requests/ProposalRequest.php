<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

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
            'project' => ['required', 'exists:projects,id'],
            'coverLetter' => ['required', 'max:4000'],
            'bid' => ['required', 'numeric'],
            'duration' => ['required', 'numeric'],

        ];
    }

    public function messages(): array
    {
        return [
            'project.required' => 'Please provide a project in order to create a proposal',
            'project.exists' => 'no recored was found for this project',
            'coverLetter.required' => 'Cover letter is required',
            'coverLetter.max' => 'Cover letter should not exceed 4000 characters',
            'bid.required' => 'Project bid is required',
            'bid.numeric' => 'Project bid should be a valid number ',
            'duration.required' => 'Project duration is required',
            'duration.numeric' => 'Project duration should be a valid number ',


        ];
    }
}