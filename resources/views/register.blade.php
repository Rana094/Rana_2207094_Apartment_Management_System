@extends('layouts.public')

@section('title', 'Resident Register — Nestora')

@section('content')
<style>
    .auth-wrapper {
        min-height: 80vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 3rem 1.5rem;
        background-color: var(--bg-main);
    }
    .auth-card {
        background-color: #ffffff;
        border: 1px solid var(--border-color);
        border-radius: var(--radius-lg);
        width: 100%;
        max-width: 580px;
        padding: 2.5rem;
        box-shadow: var(--shadow-lg);
    }
    .auth-header {
        text-align: center;
        margin-bottom: 2rem;
    }
    .auth-header h1 {
        font-size: 1.75rem;
        margin-bottom: 0.5rem;
    }
    
    /* File Upload Input styling */
    .file-upload-wrapper {
        position: relative;
        border: 2px dashed var(--border-color);
        border-radius: var(--radius-md);
        padding: 1.5rem;
        text-align: center;
        background-color: var(--bg-main);
        cursor: pointer;
        transition: var(--transition-fast);
    }
    .file-upload-wrapper:hover {
        border-color: var(--primary-color);
        background-color: var(--primary-light);
    }
    .file-upload-input {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        opacity: 0;
        cursor: pointer;
    }
    .file-upload-placeholder {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 0.5rem;
        color: var(--text-secondary);
    }
    .file-upload-placeholder svg {
        width: 2rem;
        height: 2rem;
        color: var(--text-muted);
    }
    .flat-options {
        border: 1px solid var(--border-color);
        border-radius: var(--radius-md);
        max-height: 10rem;
        overflow-y: auto;
        background: #ffffff;
    }
    .flat-option {
        border-bottom: 1px solid var(--border-color);
        padding: 0.65rem 0.75rem;
        display: flex;
        gap: 0.6rem;
        align-items: center;
        cursor: pointer;
        background: #ffffff;
        min-height: 2.75rem;
    }
    .flat-option:last-child {
        border-bottom: 0;
    }
    .flat-option:has(input:checked) {
        background: var(--primary-light);
    }
    .flat-option input {
        flex-shrink: 0;
    }
    .flat-option-main {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 0.75rem;
        width: 100%;
        min-width: 0;
    }
    .flat-option-building {
        color: var(--text-muted);
        font-size: 0.75rem;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
</style>

<div class="auth-wrapper">
    <div class="auth-card">
        <div class="auth-header">
            <h1>Create Resident Account</h1>
            <p style="color: var(--text-secondary); font-size: 0.95rem;">Join Nestora. Enter your flat details to request access from your building manager.</p>
        </div>
        
        @if ($errors->any())
            <div style="background: var(--bg-rejected); color: var(--color-rejected); padding: 1rem; border-radius: var(--radius-md); margin-bottom: 1rem;">
                Please correct the highlighted fields below.
            </div>
        @endif

        {{-- Submits to AuthController@register; enctype is required for optional NID/lease file upload. --}}
        <form action="{{ route('register.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <!-- Full Name -->
            <div class="form-group">
                <label for="reg-name" class="form-label">Full Name</label>
                <input type="text" id="reg-name" name="name" class="form-control @error('name') is-invalid @enderror" placeholder="e.g. shorif" value="{{ old('name') }}" required>
                @error('name')
                    <div class="text-xs" style="color: var(--color-rejected); margin-top: 0.35rem;">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="grid grid-2">
                <!-- Email -->
                <div class="form-group">
                    <label for="reg-email" class="form-label">Email Address</label>
                    <input type="email" id="reg-email" name="email" class="form-control @error('email') is-invalid @enderror" placeholder="e.g. shorif@example.com" value="{{ old('email') }}" required autocomplete="email">
                    @error('email')
                        <div class="text-xs" style="color: var(--color-rejected); margin-top: 0.35rem;">{{ $message }}</div>
                    @enderror
                </div>
                
                <!-- Phone -->
                <div class="form-group">
                    <label for="reg-phone" class="form-label">Phone Number</label>
                    <input type="tel" id="reg-phone" name="phone" class="form-control @error('phone') is-invalid @enderror" placeholder="e.g. +880 1711 223344" value="{{ old('phone') }}" required>
                    @error('phone')
                        <div class="text-xs" style="color: var(--color-rejected); margin-top: 0.35rem;">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            
            <div class="grid grid-2">
                <!-- Password -->
                <div class="form-group">
                    <label for="reg-password" class="form-label">Password</label>
                    <input type="password" id="reg-password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Min. 8 characters" required>
                    @error('password')
                        <div class="text-xs" style="color: var(--color-rejected); margin-top: 0.35rem;">{{ $message }}</div>
                    @enderror
                </div>
                
                <!-- Confirm Password -->
                <div class="form-group">
                    <label for="reg-password-confirm" class="form-label">Confirm Password</label>
                    <input type="password" id="reg-password-confirm" name="password_confirmation" class="form-control @error('password') is-invalid @enderror" placeholder="Confirm password" required>
                </div>
            </div>
            
            <div class="grid grid-2">
                <!-- Resident Type -->
                <div class="form-group">
                    <label for="reg-type" class="form-label">Resident Type</label>
                    <select id="reg-type" name="resident_type" class="form-control form-select @error('resident_type') is-invalid @enderror" required>
                        <option value="" disabled @selected(! old('resident_type'))>Select status...</option>
                        <option value="owner" @selected(old('resident_type') === 'owner')>Flat Owner</option>
                        <option value="tenant" @selected(old('resident_type') === 'tenant')>Tenant / Renting</option>
                    </select>
                    @error('resident_type')
                        <div class="text-xs" style="color: var(--color-rejected); margin-top: 0.35rem;">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label class="form-label">Available Flat</label>
                    {{-- $availableFlats comes from AuthController@showRegister using Flat::availableForSignup(). --}}
                    @if ($availableFlats->isNotEmpty())
                        <div class="flat-options">
                            @foreach ($availableFlats as $flat)
                                <label class="flat-option">
                                    <input type="radio" name="flat_id" value="{{ $flat->id }}" @checked((string) old('flat_id') === (string) $flat->id) required>
                                    <span class="flat-option-main">
                                        <strong>{{ $flat->flat_number }}</strong>
                                        <span class="flat-option-building">
                                            {{ $flat->building?->name ?? 'Building' }}{{ $flat->floor !== null ? ' - Floor '.$flat->floor : '' }}
                                        </span>
                                    </span>
                                </label>
                            @endforeach
                        </div>
                    @else
                        <div class="alert alert-danger">No flats are available for now. Thanks for your interest.</div>
                    @endif
                    @error('flat_id')
                        <div class="text-xs" style="color: var(--color-rejected); margin-top: 0.35rem;">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            
            <!-- Document / NID Upload Placeholder -->
            <div class="form-group">
                <label class="form-label">National ID / Lease Agreement Copy <span class="text-muted" style="font-weight: normal;">(Optional placeholder)</span></label>
                <div class="file-upload-wrapper" id="file-drop-area">
                    <input type="file" id="reg-doc" name="nid_document" class="file-upload-input" accept=".pdf,.png,.jpg,.jpeg,.doc,.docx">
                    <div class="file-upload-placeholder">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 16.5V9.75m0 0 3 3m-3-3-3 3M6.75 19.5a4.5 4.5 0 0 1-1.41-8.775 5.25 5.25 0 0 1 10.233-2.33 3 3 0 0 1 3.758 3.848A3.752 3.752 0 0 1 18 19.5H6.75Z" />
                        </svg>
                        <span style="font-weight: 600; font-size: 0.9rem;">Upload verification document</span>
                        <span style="font-size: 0.75rem; color: var(--text-muted);">PDF, Word, JPG or PNG format (Max. 5MB)</span>
                        <span id="file-chosen-name" style="font-size: 0.8rem; color: var(--primary-color); font-weight: 600; display: none;"></span>
                    </div>
                </div>
                @error('nid_document')
                    <div class="text-xs" style="color: var(--color-rejected); margin-top: 0.35rem;">{{ $message }}</div>
                @enderror
            </div>
            
            <button type="submit" class="btn btn-primary" style="width: 100%; justify-content: center; margin-top: 0.5rem; margin-bottom: 1.5rem;">
                Submit Registration Request
            </button>
            
            <div style="text-align: center; font-size: 0.9rem; color: var(--text-secondary);">
                Already have an account? <a href="{{ url('/login') }}" style="font-weight: 600;">Sign In</a>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const fileInput = document.getElementById('reg-doc');
        const fileNameSpan = document.getElementById('file-chosen-name');
        const passwordInput = document.getElementById('reg-password');
        const passwordConfirmInput = document.getElementById('reg-password-confirm');

        function validatePasswordMatch() {
            if (!passwordInput || !passwordConfirmInput) {
                return;
            }

            const mismatch = passwordConfirmInput.value && passwordInput.value !== passwordConfirmInput.value;
            passwordConfirmInput.setCustomValidity(mismatch ? 'Password and confirm password do not match.' : '');
        }

        if (passwordInput && passwordConfirmInput) {
            // Frontend check gives instant feedback; RegisterResidentRequest still validates on backend.
            passwordInput.addEventListener('input', validatePasswordMatch);
            passwordConfirmInput.addEventListener('input', validatePasswordMatch);
        }
        
        if (fileInput && fileNameSpan) {
            // Only displays selected filename; the actual file is validated/stored by backend.
            fileInput.addEventListener('change', function() {
                if (this.files && this.files.length > 0) {
                    fileNameSpan.textContent = 'Selected: ' + this.files[0].name;
                    fileNameSpan.style.display = 'block';
                } else {
                    fileNameSpan.style.display = 'none';
                }
            });
        }
    });
</script>
@endsection
