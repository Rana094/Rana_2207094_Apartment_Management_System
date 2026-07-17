<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Throwable;

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

        try {
            $request->user()->sendEmailVerificationNotification();
        } catch (Throwable $exception) {
            report($exception);

            return back()->withErrors([
                'email' => 'The verification email could not be sent. Please try again shortly.',
            ]);
        }

        return back()->with('status', 'Verification email sent.');
    }
}
