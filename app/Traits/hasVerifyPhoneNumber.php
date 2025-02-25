<?php

namespace App\Traits;



trait hasVerifyPhoneNumber
{

    function hasVerifiedPhoneNumber()
    {
        return !is_null($this->mobile_verified_at);
    }

    function markPhoneNumberAsVerified()
    {
        return $this->forceFill([
            'mobile_verified_at' => $this->freshTimestamp(),
        ])->save();

    }

    function sendPhoneNumberSmsMessage()
    {

    }

}