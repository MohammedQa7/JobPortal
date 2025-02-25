<?php

namespace App\Actions;

use App\Models\Proposal;

class MarkIsReviewed
{
    public function handle(Proposal $proposal)
    {
        $proposal->update([
            'is_reviewed' => true,
        ]);


        // notify the user.
    }
}