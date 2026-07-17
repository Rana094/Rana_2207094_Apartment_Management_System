@extends('layouts.public')

@section('title', 'Contact Support — Nestora')

@section('content')
<style>
    .contact-container {
        display: grid;
        grid-template-columns: 1fr;
        gap: 3rem;
        padding-top: 2rem;
        padding-bottom: 2rem;
    }
    @media (min-width: 768px) {
        .contact-container {
            grid-template-columns: 1fr 1.5fr;
        }
    }
    .info-panel {
        background-color: var(--primary-color);
        color: #ffffff;
        border-radius: var(--radius-lg);
        padding: 3rem;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        box-shadow: var(--shadow-md);
        position: relative;
        overflow: hidden;
    }
    .info-panel::after {
        content: '';
        position: absolute;
        bottom: -50px;
        right: -50px;
        width: 180px;
        height: 180px;
        background-color: rgba(255,255,255,0.08);
        border-radius: var(--radius-full);
    }
    .info-panel h2 {
        color: #ffffff;
        font-size: 1.75rem;
        margin-bottom: 1rem;
    }
    .info-panel p {
        color: rgba(255, 255, 255, 0.85);
        font-size: 0.95rem;
        line-height: 1.6;
        margin-bottom: 2.5rem;
    }
    .contact-item {
        display: flex;
        align-items: flex-start;
        gap: 1rem;
        margin-bottom: 1.5rem;
    }
    .contact-item svg {
        width: 1.5rem;
        height: 1.5rem;
        color: var(--secondary-light);
        flex-shrink: 0;
        margin-top: 0.15rem;
    }
    .contact-item-title {
        font-weight: 700;
        font-size: 0.95rem;
        margin-bottom: 0.15rem;
        color: #ffffff;
    }
    .contact-item-content {
        font-size: 0.9rem;
        color: rgba(255, 255, 255, 0.85);
    }
    
    .form-panel {
        background-color: #ffffff;
        border: 1px solid var(--border-color);
        border-radius: var(--radius-lg);
        padding: 3rem;
        box-shadow: var(--shadow-sm);
    }
    .form-panel h3 {
        font-size: 1.5rem;
        margin-bottom: 0.5rem;
    }
    .form-panel p {
        font-size: 0.9rem;
        color: var(--text-secondary);
        margin-bottom: 2rem;
    }
    .map-panel {
        background: #ffffff;
        border: 1px solid var(--border-color);
        border-radius: var(--radius-lg);
        box-shadow: var(--shadow-sm);
        margin-top: 2rem;
        overflow: hidden;
    }
    .map-panel img {
        display: block;
        height: 360px;
        object-fit: cover;
        width: 100%;
    }
</style>

<section class="section-padding" style="background-color: var(--bg-main);">
    <div class="container">
        
        <div style="text-align: center; margin-bottom: 4rem;">
            <h1 style="font-size: 2.5rem; margin-bottom: 0.75rem;">Get in Touch</h1>
            <p style="color: var(--text-secondary); max-width: 600px; margin: 0 auto; font-size: 1.1rem;">Have questions about pricing, setup, or features? Send us a message and our support team will respond shortly.</p>
        </div>

        <div class="contact-container">
            <!-- Left Panel: Contact Information -->
            <div class="info-panel">
                <div>
                    <h2>Contact Information</h2>
                    <p>Have inquiries? Fill out the contact form, or reach out directly using the details below.</p>
                    
                    <div class="contact-item">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-1.514 2.019a15.753 15.753 0 01-6.758-6.758l2.019-1.514c.361-.272.527-.734.417-1.173L6.963 3.106a1.125 1.125 0 00-1.091-.852H3.75A2.25 2.25 0 001.5 3.75v3z" />
                        </svg>
                        <div>
                            <div class="contact-item-title">Call Us Directly</div>
                            <div class="contact-item-content">+880 1234 567890<br>+880 9876 543210</div>
                        </div>
                    </div>
                    
                    <div class="contact-item">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
                        </svg>
                        <div>
                            <div class="contact-item-title">Email Support</div>
                            <div class="contact-item-content">support@nestora.com<br>info@nestora.com</div>
                        </div>
                    </div>
                    
                    <div class="contact-item">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25s-7.5-4.108-7.5-11.25g3 3 0 116 0z" />
                        </svg>
                        <div>
                            <div class="contact-item-title">Headquarters</div>
                            <div class="contact-item-content">12/A, Road 5, Dhanmondi R/A,<br>Dhaka - 1209, Bangladesh</div>
                        </div>
                    </div>
                </div>
                
                <div style="font-size: 0.8rem; color: rgba(255,255,255,0.7); margin-top: 2rem;">
                    Nestora Operations Hub. Operating 24/7 for security dispatch.
                </div>
            </div>

            <!-- Right Panel: Message Form -->
            <div class="form-panel">
                <h3>Send a Message</h3>
                <p>We typically respond within 2 hours during business operations.</p>
                
                @if(session('success'))
                    <div class="badge badge-approved" style="display: block; padding: 0.75rem 1rem; border-radius: var(--radius-md); font-size: 0.9rem; margin-bottom: 1.5rem;">
                        {{ session('success') }}
                    </div>
                @endif
                
                <form action="{{ route('contact.store') }}" method="POST">
                    @csrf
                    <div class="grid grid-2">
                        <div class="form-group">
                            <label for="contact-name" class="form-label">Full Name</label>
                            <input type="text" id="contact-name" name="name" class="form-control" value="{{ old('name') }}" placeholder="e.g. ullas" required>
                        </div>
                        <div class="form-group">
                            <label for="contact-email" class="form-label">Email Address</label>
                            <input type="email" id="contact-email" name="email" class="form-control" value="{{ old('email') }}" placeholder="e.g. ullas@example.com" required>
                        </div>
                    </div>
                    
                    <div class="grid grid-2">
                        <div class="form-group">
                            <label for="contact-phone" class="form-label">Phone Number</label>
                            <input type="tel" id="contact-phone" name="phone" class="form-control" value="{{ old('phone') }}" placeholder="e.g. +880 1711 223344">
                        </div>
                        <div class="form-group">
                            <label for="contact-subject" class="form-label">Subject</label>
                            <input type="text" id="contact-subject" name="subject" class="form-control" value="{{ old('subject') }}" placeholder="e.g. Request Demo / Setup Dues" required>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="contact-message" class="form-label">Message Details</label>
                        <textarea id="contact-message" name="message" class="form-control" rows="5" placeholder="Type your questions or description of your society here..." required>{{ old('message') }}</textarea>
                    </div>
                    
                    <div style="text-align: right; margin-top: 1rem;">
                        <button type="submit" class="btn btn-primary" style="width: 100%; justify-content: center;">Send Message</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="map-panel">
            <img src="{{ route('contact.map') }}" alt="Nestora apartment location map">
        </div>
    </div>
</section>
@endsection
