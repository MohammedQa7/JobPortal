<?php

namespace App\Http\Controllers;

use App\Http\Requests\MobileVerificationRequest;
use App\Models\OtpCodes;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class MobileVerificationController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function create()
    {
        return Inertia::render('Auth/PhoneVerification');
    }

    public function send(MobileVerificationRequest $request)
    {
        $is_there_active_code = OtpCodes::where('user_id', auth()->id())
            ->where('expires_at', '>', now())->exists();

        $is_there_active_code
            ? to_route('mobile.verification.show')
            : OtpCodes::create([
                'user_id' => auth()->user()->id,
                'code' => rand(10000, 99999),
                'expires_at' => now()->addMinutes(5),
            ]);


        return to_route('mobile.verification.show');


    }

    public function show()
    {
        return Inertia::render('Auth/Verify');
    }


    public function verify(Request $request)
    {
        $OTP = OtpCodes::where('code', $request->code)
            ->where('user_id', auth()->id())
            ->where('expires_at', '>', now())->first();
        if ($OTP) {
            auth()->user()->markPhoneNumberAsVerified();
            return to_route('job.index');
        } else {
            $error = ValidationException::withMessages([
                'code' => ['Invalid Code.'],
            ]);
            throw $error;
        }
    }
}