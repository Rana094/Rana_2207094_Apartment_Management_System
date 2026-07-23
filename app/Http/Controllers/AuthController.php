<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\RegisterResidentRequest;
use App\Models\Flat;
use App\Models\User;
use App\Services\FileUploadService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthController extends Controller
{
    /**
     * Show the login form for all portal users.
     */
    public function showLogin(): View
    {
        return view('login');
    }

    /**
     * Validate credentials, create the session, and send each role to its dashboard.
     */
    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $remember = $request->boolean('remember');

        // Auth::attempt checks the submitted password against the hashed password in the database.
        if (! Auth::attempt($credentials, $remember)) {
            return back()
                ->withErrors(['email' => 'The email or password is incorrect.'])
                ->onlyInput('email');
        }

        // Regenerating the session ID protects against session fixation after login.
        $request->session()->regenerate();

        $user = $request->user();

        // Users can log in after signup, but portal access waits for manager approval.
        if (! $user->isApproved()) {
            return redirect()->route('approval.pending');
        }

        return redirect()->intended(route($user->dashboardRouteName(), absolute: false));
    }

    /**
     * Show signup form with only flats that are still available for resident requests.
     */
    public function showRegister(): View
    {
        return view('register', [
            'availableFlats' => Flat::with('building')
                ->availableForSignup()
                ->orderBy('flat_number')
                ->get(),
        ]);
    }

    /**
     * Create a pending resident account and reserve their requested flat until manager review.
     */
    public function register(RegisterResidentRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $flat = Flat::with('building')->findOrFail($validated['flat_id']);

        // NID/verification document is optional but stored privately when provided.
        $documentPath = $request->hasFile('nid_document')
            ? app(FileUploadService::class)->store($request->file('nid_document'), 'resident-documents')
            : null;

        // Email verification is disabled; approval is handled by the manager workflow.
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'password' => $validated['password'],
            'role' => 'resident',
            'status' => 'pending_approval',
            'resident_type' => $validated['resident_type'],
            'flat_info' => trim(($flat->building?->name ? $flat->building->name.', ' : '').'Flat '.$flat->flat_number),
            'requested_flat_id' => $flat->id,
            'document_path' => $documentPath,
        ]);

        Auth::login($user);

        return redirect()->route('approval.pending')
            ->with('status', 'Registration submitted. Please wait for manager approval.');
    }

    /**
     * End the authenticated session and rotate the CSRF token.
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
