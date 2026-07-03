<?php

namespace Tests\Feature;

use Tests\TestCase;

class PublicPagesTest extends TestCase
{
    /**
     * Test that the landing page renders successfully.
     */
    public function test_landing_page_renders_successfully(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('Nestora');
        $response->assertSee('Smart Apartment Management');
    }

    /**
     * Test that the about page renders successfully.
     */
    public function test_about_page_renders_successfully(): void
    {
        $response = $this->get('/about');
        $response->assertStatus(200);
        $response->assertSee('About Nestora');
        $response->assertSee('Our Mission');
    }

    /**
     * Test that the contact page renders successfully.
     */
    public function test_contact_page_renders_successfully(): void
    {
        $response = $this->get('/contact');
        $response->assertStatus(200);
        $response->assertSee('Get in Touch');
        $response->assertSee('Contact Information');
    }

    /**
     * Test that the login page renders successfully.
     */
    public function test_login_page_renders_successfully(): void
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
        $response->assertSee('Welcome Back');
        $response->assertSee('Sign In as');
    }

    /**
     * Test that the registration page renders successfully.
     */
    public function test_register_page_renders_successfully(): void
    {
        $response = $this->get('/register');
        $response->assertStatus(200);
        $response->assertSee('Create Resident Account');
    }

    /**
     * Test that the waiting approval page renders successfully.
     */
    public function test_waiting_approval_page_renders_successfully(): void
    {
        $response = $this->get('/waiting-approval');
        $response->assertStatus(200);
        $response->assertSee('Account Registration Submitted');
        $response->assertSee('verification and manager approval');
    }
}
