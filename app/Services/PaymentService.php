<?php

namespace App\Services;

use App\Contracts\PaymentContract;


class PaymentService
{

    function RedirectToCheckout(PaymentContract $paymentContract)
    {
        return $paymentContract->Checkout();
    }

}