@extends('layouts.public')

@section('title', 'About Nestora — Our Vision & Values')

@section('content')
<style>
    .about-hero {
        padding: 5rem 0 3rem 0;
        text-align: center;
        background: radial-gradient(circle at 90% 10%, rgba(13, 148, 136, 0.1) 0%, rgba(255, 255, 255, 0) 70%);
    }
    .about-hero h1 {
        font-size: 2.5rem;
        margin-bottom: 1rem;
    }
    .about-hero p {
        font-size: 1.15rem;
        max-width: 700px;
        margin: 0 auto;
        color: var(--text-secondary);
    }
    
    .story-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 3rem;
        align-items: center;
        margin-top: 2rem;
    }
    @media (min-width: 768px) {
        .story-grid {
            grid-template-columns: 1.2fr 1fr;
        }
    }
    
    .values-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 2rem;
        margin-top: 3rem;
    }
    @media (min-width: 768px) {
        .values-grid {
            grid-template-columns: repeat(2, 1fr);
        }
        @media (min-width: 992px) {
            .values-grid {
                grid-template-columns: repeat(4, 1fr);
            }
        }
    }
    
    .value-card {
        background: #ffffff;
        border: 1px solid var(--border-color);
        border-radius: var(--radius-lg);
        padding: 2rem;
        text-align: center;
        transition: var(--transition-normal);
    }
    .value-card:hover {
        transform: translateY(-3px);
        box-shadow: var(--shadow-md);
        border-color: var(--secondary-light);
    }
    
    .value-icon {
        width: 3rem;
        height: 3rem;
        color: var(--secondary-color);
        background-color: var(--secondary-light);
        border-radius: var(--radius-full);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.25rem auto;
    }
    .value-icon svg {
        width: 1.5rem;
        height: 1.5rem;
    }
    
    .stats-bar {
        background-color: #0f172a;
        color: #ffffff;
        padding: 4rem 0;
        text-align: center;
    }
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 2rem;
    }
    @media (min-width: 768px) {
        .stats-grid {
            grid-template-columns: repeat(4, 1fr);
        }
    }
    .stat-number {
        font-size: 2.5rem;
        font-weight: 800;
        color: var(--secondary-color);
        margin-bottom: 0.5rem;
    }
    .stat-label {
        font-size: 0.9rem;
        color: #94a3b8;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }
</style>

<!-- About Hero -->
<section class="about-hero">
    <div class="container">
        <h1>About Nestora</h1>
        <p>Providing the technological blueprint for smart, secure, and collaborative apartment living.</p>
    </div>
</section>

<!-- Our Story Section -->
<section class="section-padding" style="background-color: #ffffff;">
    <div class="container">
        <div class="story-grid">
            <div>
                <h2 style="font-size: 2rem; margin-bottom: 1.25rem;">Our Mission</h2>
                <p>Nestora was born out of a simple observation: modern apartment societies face complex daily operations, but the software they use is often fragmented, outdated, or difficult to master.</p>
                <p>We set out to create a unified apartment ecosystem that brings residents, committee managers, security personnel, and technicians onto a single cohesive platform. By automating billing, simplifying maintenance requests, and introducing smart check-ins, we replace clutter with absolute transparency.</p>
                <p>Whether you're a resident paying a utility invoice, a manager balancing the monthly books, or a guard registering a parcel arrival, Nestora ensures you have the exact tool you need right at your fingertips.</p>
            </div>
            <div>
                <div class="card-static" style="background-color: var(--bg-main); border: none; padding: 2.5rem; border-radius: var(--radius-lg);">
                    <h3 style="margin-bottom: 1rem; color: var(--primary-color);">Why the name "Nestora"?</h3>
                    <p style="font-size: 0.95rem; line-height: 1.6; margin-bottom: 1.5rem;">The name is inspired by "Nest" (a safe and comfortable home) and "Agora" (a central public space for community gatherings in ancient Greece). Nestora represents the perfect fusion of safe home lives and active, well-managed community living.</p>
                    
                    <div class="flex items-center gap-3">
                        <svg class="logo-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 2rem; height: 2rem;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 21v-4.875c0-.621.504-1.125 1.125-1.125h5.25c.621 0 1.125.504 1.125 1.125V21m0 0h4.5V3.545M2.25 9l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 9M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                        </svg>
                        <div>
                            <p style="font-weight: 700; margin-bottom: 0; font-size: 1rem; color: var(--text-primary);">Nestora Ltd.</p>
                            <p style="font-size: 0.8rem; color: var(--text-secondary); margin-bottom: 0;">Smart Apartment Management Made Simple</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Statistics Bar -->
<section class="stats-bar">
    <div class="container">
        <div class="stats-grid">
            <div>
                <div class="stat-number">250+</div>
                <div class="stat-label">Societies Managed</div>
            </div>
            <div>
                <div class="stat-number">45K+</div>
                <div class="stat-label">Active Residents</div>
            </div>
            <div>
                <div class="stat-number">99.8%</div>
                <div class="stat-label">Uptime Record</div>
            </div>
            <div>
                <div class="stat-number">15 Mins</div>
                <div class="stat-label">Average Support Response</div>
            </div>
        </div>
    </div>
</section>

<!-- Values Section -->
<section class="section-padding" style="background-color: var(--bg-main);">
    <div class="container">
        <div style="text-align: center; margin-bottom: 4rem;">
            <h2 style="font-size: 2rem; margin-bottom: 0.5rem;">Our Core Pillars</h2>
            <p style="color: var(--text-secondary); max-width: 600px; margin: 0 auto;">Four foundational values guide every feature we design for your residential society.</p>
        </div>

        <div class="values-grid">
            <!-- Pillar 1: Security -->
            <div class="value-card">
                <div class="value-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z" />
                    </svg>
                </div>
                <h3 style="font-size: 1.15rem; margin-bottom: 0.75rem;">Perimeter Safety</h3>
                <p style="font-size: 0.875rem; color: var(--text-secondary); line-height: 1.6; margin-bottom: 0;">Securing gate access with check-in verification codes, instant arrival notifications, and panic systems.</p>
            </div>

            <!-- Pillar 2: Transparency -->
            <div class="value-card">
                <div class="value-icon" style="background-color: var(--primary-light); color: var(--primary-color);">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </div>
                <h3 style="font-size: 1.15rem; margin-bottom: 0.75rem;">Clear Bookkeeping</h3>
                <p style="font-size: 0.875rem; color: var(--text-secondary); line-height: 1.6; margin-bottom: 0;">Offering detailed billing history, verification logs, and digital payment receipts to minimize disputes.</p>
            </div>

            <!-- Pillar 3: Efficiency -->
            <div class="value-card">
                <div class="value-icon" style="background-color: #fef3c7; color: #d97706;">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z" />
                    </svg>
                </div>
                <h3 style="font-size: 1.15rem; margin-bottom: 0.75rem;">Operational Speed</h3>
                <p style="font-size: 0.875rem; color: var(--text-secondary); line-height: 1.6; margin-bottom: 0;">Reducing response latency for maintenance issues, booking approvals, and urgent tasks with automated flows.</p>
            </div>

            <!-- Pillar 4: Community -->
            <div class="value-card">
                <div class="value-icon" style="background-color: #f5f3ff; color: #7c3aed;">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.97 5.97 0 00-.75-2.906m-.179-1.973a5 5 0 00-3.328-4.47m-.023-.01a4.247 4.247 0 001.137-3.203a4.25 4.25 0 00-6.505-3.475m-.022.012a4.247 4.247 0 00-1.138 3.203a4.25 4.25 0 005.662 5.072m.002.012a4.25 4.25 0 00-.002-8.25" />
                    </svg>
                </div>
                <h3 style="font-size: 1.15rem; margin-bottom: 0.75rem;">Healthy Ties</h3>
                <p style="font-size: 0.875rem; color: var(--text-secondary); line-height: 1.6; margin-bottom: 0;">Keeping residents informed through central notice boards and simple booking of shared venues.</p>
            </div>
        </div>
    </div>
</section>
@endsection
