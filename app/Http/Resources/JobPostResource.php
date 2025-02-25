<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class JobPostResource extends JsonResource
{

    public $all_skills = false; // Custom property for external data

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'description' => $this->formatDescriptionOnRouter($request),
            'budget' => $this->budget,
            'duration' => $this->duration,
            'status' => $this->status,
            'user' => new UserResource($this->whenLoaded('user')),
            'proposal' => when($this->relationLoaded('proposals') && sizeof($this->proposals) > 0, function () {
                return ProposalResource::collection($this->whenLoaded('proposals'))->first();
            }),
            'skills' => $this->formatSkillsOnCondition($request),
            'attachments' => when($this->relationLoaded('attachments'), function () {
                return AttachmentResource::collection($this->attachments);
            }),
            'proposalsCount' => $this->proposals_count,
            'skillCount' => count(collect(explode(',', $this->skills))
                ->chunk(6)
                ->skip(1)
                ->flatten()),
            'hasUserSubmitProposal' => auth()->user()
            ? auth()->user()->hasSubmitedProposal($this->resource)
            : null,

            'createdAt' => $this->formatCreatedAtOnRouter($request),
        ];


    }



    function formatCreatedAtOnRouter($request)
    {
        if ($request->routeIs('proposal.create')) {
            return $this->created_at->format('M d, Y');
        }
        return $this->created_at->diffForHumans();
    }

    function formatDescriptionOnRouter($request)
    {
        if ($request->routeIs('job.index.modal') || $request->routeIs('proposal.create')) {
            return $this->description;
        }
        return Str::limit($this->description, 50);
    }

    function formatSkillsOnCondition($request)
    {
        if ($this->all_skills) {
            return explode(',', $this->skills);
        }
        return array_slice(explode(',', $this->skills), 0, 6);
    }


}