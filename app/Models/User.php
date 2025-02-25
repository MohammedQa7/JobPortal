<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Contracts\MustVerifyPhoneNumber;
use App\Enum\JobPostStatus;
use App\Enum\ProjectStatus;
use App\Traits\hasVerifyPhoneNumber;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyPhoneNumber
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, hasVerifyPhoneNumber;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'bio',
        'phone_number',
        'id_front_image',
        'id_back_image',
        'job_title',
        'specialty_id',
        'skill',
        'balance'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Helper functions
    function isProfileCompleted()
    {
        $profile_completion_indicator = [
            'phone_number',
            'id_front_image',
            'id_back_image',
            'job_title',
            'specialty_id',
        ];

        return collect($profile_completion_indicator)->contains(null);
    }

    public function hasSubmitedProposal($job)
    {
        if (!$this->relationLoaded('proposals')) {
            $this->load('proposals');
        }

        return $this->proposals->where('job_post_id', $job->id)->isNotEmpty();
    }

    public function hasSubmitedOffer($job_seeker, $job)
    {

        if (!$this->relationLoaded('offers')) {
            $this->load('offers');
        }
        return $this->offers->where('job_seeker_id', $job_seeker)
            ->where('job_post_id', $job->id)
            ->isNotEmpty();
    }

    public function hasRatedJob($job)
    {
        if (!$this->relationLoaded('rating.job')) {
            $this->load([
                'rating.job' => function ($query) {
                    $query->withoutGlobalScopes();
                }
            ]);
        }


        return $this->rating->where('job_post_id', $job->id)->isNotEmpty();
    }

    public function canRateJob($job)
    {
        return in_array($job->status, [JobPostStatus::CANCELED->value, JobPostStatus::COMPLETED->value]);
    }

    public function jobsCount()
    {
        if (!$this->relationLoaded('jobs')) {
            $this->load('jobs');
        }
        return $this->jobs->count();
    }

    public function jobsCompletionRate()
    {
        if (!$this->relationLoaded('jobs')) {
            $this->load('jobs');
        }
        $job_count = $this->jobs->count();
        $assigned_jobs = $this->jobs
            ->where('status', JobPostStatus::COMPLETED->value)
            ->whereNotNull('assigned_user')
            ->count();

        if ($job_count > 0) {
            $percentage = ($assigned_jobs / $job_count) * 100;
            return number_format(round($percentage, 2), 2);
        }

    }

    // Relationships

    public function jobs()
    {
        return $this->hasMany(JobPost::class, 'user_id');
    }

    public function assignedProject()
    {
        return $this->hasMany(JobPost::class, 'assigned_user');
    }


    // Job Seeker proposals
    public function proposals()
    {
        return $this->hasMany(Proposal::class, 'job_seeker_id');
    }

    // Client proposals / offers
    public function offers()
    {
        return $this->hasMany(Proposal::class, 'client_id');
    }

    public function rating()
    {
        return $this->hasMany(Rating::class);
    }

    public function ClientEscrow()
    {
        return $this->hasMany(Escrow::class, 'client_id');
    }

    public function SeekerEscrow()
    {
        return $this->hasMany(Escrow::class, 'job_seeker_id');
    }

    public function requests()
    {
        return $this->hasMany(JobRequest::class);
    }

    public function otpCode()
    {
        return $this->hasMany(OtpCodes::class);
    }
}