<?php

namespace App\Http\Controllers;

use App\Actions\UpdateUserBalance;
use App\Enum\UpdateUserBalanceTypes;
use Illuminate\Http\Request;
use Stripe\Checkout\Session;
use Stripe\Stripe;

class UserBalanceController extends Controller
{
    function __invoke(Request $request, UpdateUserBalance $action)
    {
        $stripe_session_id = $request->session_id;
        if ($stripe_session_id) {
            Stripe::setApiKey(config('services.stripe.secret'));
            $session = Session::retrieve($stripe_session_id);
            $action->handle(UpdateUserBalanceTypes::INCREMENT->value, $session->amount_total / 100);

            return to_route('job.index');
        }
    }
}