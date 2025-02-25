<?php

namespace App\Payments;

use App\Contracts\PaymentContract;
use Stripe\Checkout\Session;
use Stripe\Stripe;

class StripePayment implements PaymentContract
{
    protected $price;
    function __construct($price)
    {
        $this->price = $price;
    }
    function Checkout()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
        $checkoutSession = Session::create([
            'line_items' => [
                [
                    'price_data' => [
                        'currency' => 'usd',
                        'product_data' => [
                            'name' => 'Charging Balance',
                        ],
                        'unit_amount' => $this->price,
                    ],
                    'quantity' => 1,
                ]
            ],
            'mode' => 'payment',
            'success_url' => route('success') . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('cancel'),
        ]);

        return response()->json([
            'checkoutUrl' => $checkoutSession->url,
        ]);
    }
}