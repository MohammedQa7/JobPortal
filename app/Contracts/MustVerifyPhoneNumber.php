<?php

namespace App\Contracts;

interface MustVerifyPhoneNumber
{
    /**
     * Determine if the user has verified their phone number.
     *
     * @return bool
     */
    public function hasVerifiedPhoneNumber();

    /**
     * Mark the given user's email as verified.
     *
     * @return bool
     */
    public function markPhoneNumberAsVerified();

    /**
     * Send the email verification notification.
     *
     * @return void
     */
    public function sendPhoneNumberSmsMessage();
}