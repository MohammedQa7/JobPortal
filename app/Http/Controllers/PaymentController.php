<?php

namespace App\Http\Controllers;

use App\Payments\StripePayment;
use App\Services\PaymentService;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function __invoke(Request $request, PaymentService $paymentService)
    {
        $request->validate([
            'price' => ['required', 'numeric', 'min:5'],
        ]);

        $stripe = new StripePayment($request->price);
        return $paymentService->RedirectToCheckout($stripe);
    }
}