<?php

namespace App\Models;

use App\Enum\ProposalTypes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Proposal extends Model
{

    protected $guarded = [
        'id',
        'created_at'
    ];


    function getRouteKeyName()
    {
        return 'uuid';
    }

    // helper function

    public function canInviteSeeker()
    {
        return !$this->invited;
    }

    // Relationships
    public function job()
    {
        return $this->belongsTo(JobPost::class, 'job_post_id');
    }

    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    public function jobSeeker()
    {
        return $this->belongsTo(User::class, 'job_seeker_id');
    }

    public function escrow()
    {
        return $this->hasMany(Escrow::class);
    }


    // SCOPED

    public function scopeSearchByType($query, $type)
    {
        $query->when(
            $type == 'invites',
            function ($query) {
                $query->where('type', ProposalTypes::OFFER->value);
            },
            function ($query) {
                $query->where('type', ProposalTypes::PROPOSAL->value);
            }
        );
    }
}