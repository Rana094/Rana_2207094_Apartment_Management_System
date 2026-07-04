<?php

namespace Tests\Feature;

use Tests\TestCase;

class DashboardPreviewTest extends TestCase
{
    /**
     * Test that the dashboard preview page renders for default resident role.
     */
    public function test_dashboard_preview_renders_for_resident(): void
    {
        $response = $this->get('/dashboard-preview?role=resident');
        
        $response->assertStatus(200);
        $response->assertSee('Resident Portal');
        $response->assertSee('Resident Hub');
        $response->assertSee('My Flat');
        $response->assertSee('UI Kit and Layout Component Preview');
        $response->assertSee('Total Residents');
    }

    /**
     * Test that the dashboard preview page renders and switches to manager sidebar.
     */
    public function test_dashboard_preview_renders_for_manager(): void
    {
        $response = $this->get('/dashboard-preview?role=manager');
        
        $response->assertStatus(200);
        $response->assertSee('Building Manager Portal');
        $response->assertSee('Management Hub');
        $response->assertSee('Resident Approvals');
        $response->assertSee('Owner/Tenant Records');
    }

    /**
     * Test that the dashboard preview page renders for security guard role.
     */
    public function test_dashboard_preview_renders_for_security(): void
    {
        $response = $this->get('/dashboard-preview?role=security');
        
        $response->assertStatus(200);
        $response->assertSee('Gate Security Portal');
        $response->assertSee('Security Desk');
        $response->assertSee('Visitor Check-In');
    }

    /**
     * Test that the dashboard preview page renders for maintenance staff role.
     */
    public function test_dashboard_preview_renders_for_staff(): void
    {
        $response = $this->get('/dashboard-preview?role=staff');
        
        $response->assertStatus(200);
        $response->assertSee('Maintenance Staff Portal');
        $response->assertSee('Technician Desk');
        $response->assertSee('Assigned Work Orders');
        $response->assertSee('Completed Work');
    }

    /**
     * Test that all UI kit showcase elements render successfully.
     */
    public function test_dashboard_preview_shows_ui_elements(): void
    {
        $response = $this->get('/dashboard-preview');
        
        // Assert badges exist
        $response->assertSee('pending');
        $response->assertSee('approved');
        $response->assertSee('rejected');
        
        // Assert table components exist
        $response->assertSee('Resident Info');
        $response->assertSee('Farhan Chowdhury');
        $response->assertSee('Kabir Hossain');
        
        // Assert modal triggers are on the page
        $response->assertSee('Danger Modal Trigger');
    }
}
