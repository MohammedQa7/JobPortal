<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class JobPostRequest extends FormRequest
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
            'title' => ['required', 'min:4', 'max:255'],
            'description' => ['nullable', 'max:4000'],
            'budget' => ['required', 'numeric', 'integer', 'min:5', 'max:10000000'],
            'duration' => ['required', 'min:1'],
            'selectedCategory' => ['required', 'exists:categories,id'],
            'skills' => ['required', 'max:8'],

        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Please provide a title for the job',
            'budget.required' => 'Budget is required',
            'budget.numeric' => 'Budget should be valid (provide numbers only)',
            'budget.min' => 'Budget should be at least $5',
            'budget.max' => 'Budget shoudl not exceed $1000000',
            'budget.integer' => 'Please enter a valid budget (we do not accpet float numbers like 0.01)',
            'duration.required' => 'Duration is required',
            'duration.min' => 'Budget should be at least 1 Day',
            'selectedCategory.required' => 'Category is required',
            'selectedCategory.exists' => 'Please choose a valid category',
            'skills.required' => 'Skills is required',
            'skills.max' => 'maximum skills is 8',

        ];
    }
}