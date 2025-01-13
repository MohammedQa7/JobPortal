<?php

namespace App\Models;

use App\Models\Attachment;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Project extends Model
{
    use HasFactory;
    protected $guarded = [
        'id',
        'created_at'
    ];

    public function getRouteKeyName()
    {
        return 'slug';
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
        return $this->hasMany(Proposal::class);
    }
    public function rating()
    {
        return $this->hasMany(Rating::class);
    }
}