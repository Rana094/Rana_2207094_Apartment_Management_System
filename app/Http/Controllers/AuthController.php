<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\FileUploadService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;
use Throwable;

class AuthController extends Controller
{
    public function showLogin(): View
    {
        return view('login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
            'role' => ['required', Rule::in(['resident', 'manager', 'security', 'staff'])],
        ]);

        $remember = $request->boolean('remember');
        $role = $credentials['role'];
        unset($credentials['role']);

        if (! Auth::attempt($credentials + ['role' => $role], $remember)) {
            return back()
                ->withErrors(['email' => 'The selected role, email, or password is incorrect.'])
                ->onlyInput('email', 'role');
        }

        $request->session()->regenerate();

        $user = $request->user();

        if (! $user->hasVerifiedEmail() || ! $user->isApproved()) {
            return redirect()->route('approval.pending');
        }

        return redirect()->intended(route($user->dashboardRouteName(), absolute: false));
    }

    public function showRegister(): View
    {
        return view('register');
    }

    public function register(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['required', 'string', 'max:30'],
            'password' => ['required', 'confirmed', Password::min(8)],
            'resident_type' => ['required', Rule::in(['owner', 'tenant'])],
            'flat_info' => ['required', 'string', 'max:255'],
            'nid_document' => ['nullable', 'file', 'mimes:'.FileUploadService::DOCUMENT_MIMES, 'max:'.FileUploadService::MAX_DOCUMENT_KB],
        ]);

        $documentPath = $request->hasFile('nid_document')
            ? app(FileUploadService::class)->store($request->file('nid_document'), 'resident-documents')
            : null;

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'password' => $validated['password'],
            'role' => 'resident',
            'status' => 'pending_verification',
            'resident_type' => $validated['resident_type'],
            'flat_info' => $validated['flat_info'],
            'document_path' => $documentPath,
        ]);

        $verificationEmailSent = true;

        try {
            event(new Registered($user));
        } catch (Throwable $exception) {
            report($exception);
            $verificationEmailSent = false;
        }

        Auth::login($user);

        return redirect()->route('approval.pending')
            ->with('status', $verificationEmailSent
                ? 'Registration submitted. Please verify your email, then wait for manager approval.'
                : 'Registration submitted, but the verification email could not be sent. Please use Resend Verification Email shortly.');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
