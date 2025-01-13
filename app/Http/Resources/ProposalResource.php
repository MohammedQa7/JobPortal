<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProposalResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'uuid' => $this->uuid,
            'project' => new ProjectResource($this->whenLoaded('project')),
            'user' => new UserResource($this->whenLoaded('user')),
            'coverLetter' => $this->cover_letter,
            'bid' => $this->bid,
            'duration' => $this->duration,
            'isProposalReviewed' => $this->is_reviewed,
            'canEdit' => !$this->is_reviewed && $this->editing_ends_at >= now() ? true : false,
            'createdAt' => $this->created_at->format('M d, Y'),
            'formatedCreatedAt' => $this->created_at->diffForHumans(),
        ];
    }
}