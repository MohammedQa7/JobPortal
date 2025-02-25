<?php

namespace App\Models;

use App\Enum\JobRequestsTypes;
use App\Models\Attachment;
use App\Models\Scopes\JobPostScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Laravel\Scout\Searchable;

class JobPost extends Model
{
    use HasFactory;

    protected $guarded = [
        'id',
        'created_at'
    ];

    // Global scope
    protected static function booted()
    {
        static::addGlobalScope(JobPostScope::class);
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    // Helpers
    public function isThereNotAccpetedRequests()
    {
        $this->load('requests');

        return $this->requests
            ->whereNull('is_accepted')
            ->isNotEmpty();
    }



    // Relationships

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'assinged_user');
    }

    public function attachments(): MorphMany
    {
        return $this->morphMany(Attachment::class, 'attachable');
    }

    public function proposals()
    {
        return $this->hasMany(Proposal::class, 'job_post_id');
    }

    public function scopeAcceptedProposal()
    {
        $this->load('proposals');
        return $this->proposals->where('is_accepted', true)->first();
    }

    public function rating()
    {
        return $this->hasMany(Rating::class, 'job_post_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function requests()
    {
        return $this->hasMany(JobRequest::class, 'job_post_id');
    }


    // SCOPES
    public function scopeFilterByCategory($query, $request)
    {

        if ($request->category) {
            $category_slugs = explode(' ', $request->category);
            return $query->whereHas('category', function ($query) use ($category_slugs) {
                $query->whereIn('slug', $category_slugs); // Match any of the slugs
            });
        }
    }

    public function scopeSearch($query, $search_query)
    {
        return $query->where(function ($query) use ($search_query) {
            $query->where('title', 'LIKE', "%{$search_query}%")
                ->orWhere('description', 'LIKE', "%{$search_query}%")
                ->orWhere('skills', 'LIKE', "%{$search_query}%");
        })
            ->orWhereHas('category', function ($query) use ($search_query) {
                $query->where('name', 'LIKE', "%{$search_query}%");
            });
    }




}