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
    public function showLogin(): View
    {
        return view('login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $remember = $request->boolean('remember');

        if (! Auth::attempt($credentials, $remember)) {
            return back()
                ->withErrors(['email' => 'The email or password is incorrect.'])
                ->onlyInput('email');
        }

        $request->session()->regenerate();

        $user = $request->user();

        if (! $user->isApproved()) {
            return redirect()->route('approval.pending');
        }

        return redirect()->intended(route($user->dashboardRouteName(), absolute: false));
    }

    public function showRegister(): View
    {
        return view('register', [
            'availableFlats' => Flat::with('building')
                ->availableForSignup()
                ->orderBy('flat_number')
                ->get(),
        ]);
    }

    public function register(RegisterResidentRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $flat = Flat::with('building')->findOrFail($validated['flat_id']);

        $documentPath = $request->hasFile('nid_document')
            ? app(FileUploadService::class)->store($request->file('nid_document'), 'resident-documents')
            : null;

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

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
