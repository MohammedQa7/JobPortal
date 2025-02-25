<?php

namespace App\Http\Controllers;

use App\Actions\MarkIsReviewed;
use App\Models\Proposal;
use Illuminate\Http\Request;

class isReviewedController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Proposal $proposal, Request $request, MarkIsReviewed $action)
    {

        $action->handle($proposal);

    }

}