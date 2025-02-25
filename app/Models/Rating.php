<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{

    protected $guarded = [
        'id',
        'created_at',
    ];




    // Relationships
    public function job()
    {
        return $this->belongsTo(JobPost::class, 'job_post_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // SCOPES

    function scopeGetUserRating($query , $user)
    {
        return $query->whereHas('job', function ($query) use ($user) {
            $query->withoutGlobalScopes();
            $query->where('user_id', $user->id);
        });
    }
}