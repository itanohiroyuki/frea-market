<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

class EmailController
{
    public function notice()
    {
        return view('emails.verify');
    }

    public function resend()
    {
        $user = Auth::user();
        if ($user->hasVerifiedEmail()) {
            return redirect('/setting');
        }

        $user->sendEmailVerificationNotification();
        return back()->with('status', '認証メールを再送しました');
    }

    public function verify(EmailVerificationRequest $request)
    {
        $request->fulfill();
        return redirect('/setting')->with('verified', true);
    }
}
