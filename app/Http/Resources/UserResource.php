<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'rate' => $this->whenLoaded('rating', function ($query) {
                return $query->avg('rate') ?? 0;
            }),
            'jobTitle' => $this->job_title,
            'jobsCompletionRate' => $this->jobsCompletionRate(),
            'jobsCount' => $this->jobsCount(),
            'createdAt' => $this->created_at->format('M d , Y'),
        ];
    }
}