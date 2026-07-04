<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EmailVerificationController extends Controller
{
    public function notice(): View
    {
        return view('waiting-approval');
    }

    public function verify(EmailVerificationRequest $request): RedirectResponse
    {
        $request->fulfill();

        if ($request->user()->status === 'pending_verification') {
            $request->user()->forceFill(['status' => 'pending_approval'])->save();
        }

        return redirect()->route('approval.pending')
            ->with('status', 'Email verified. Your account is now waiting for manager approval.');
    }

    public function resend(Request $request): RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->route('approval.pending');
        }

        $request->user()->sendEmailVerificationNotification();

        return back()->with('status', 'Verification email sent.');
    }
}
