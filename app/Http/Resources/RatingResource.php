<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RatingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'job' => new JobPostResource($this->whenLoaded('job')),
            'user' => new UserResource($this->whenLoaded('user')),
            'rate' => $this->rate,
            'description' => $this->description,
            'createdAt' => $this->created_at->diffForHumans()
        ];
    }
}