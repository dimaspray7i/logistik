<?php

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class SecurityProductionHardeningTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private User $customerUser;
    private Customer $customer;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'role' => UserRole::ADMIN,
            'customer_id' => null,
            'password' => bcrypt('password123'),
        ]);

        $this->customer = Customer::create([
            'name' => 'John Doe',
            'company_name' => 'PT Maju Jaya',
            'phone' => '08123456789',
            'email' => 'majujaya@example.com',
            'address' => 'Jl. Merdeka No. 1',
            'city' => 'Jakarta',
            'province' => 'DKI Jakarta',
            'postal_code' => '10110',
        ]);

        $this->customerUser = User::factory()->create([
            'role' => UserRole::CUSTOMER,
            'customer_id' => $this->customer->id,
            'password' => bcrypt('password123'),
        ]);
    }

    // ==========================================
    // 1. HTTP SECURITY HEADERS TESTS
    // ==========================================

    public function test_security_headers_are_present_on_web_responses(): void
    {
        $response = $this->get('/');

        $response->assertOk();
        $response->assertHeader('X-Content-Type-Options', 'nosniff');
        $response->assertHeader('X-Frame-Options', 'SAMEORIGIN');
        $response->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->assertHeader('Permissions-Policy', 'camera=(), microphone=(), geolocation=()');
    }

    // ==========================================
    // 2. SESSION SECURITY & REGENERATION TESTS
    // ==========================================

    public function test_session_regenerates_after_successful_login(): void
    {
        $this->get('/login');
        $initialSessionId = session()->getId();

        $response = $this->post('/login', [
            'email' => $this->admin->email,
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('dashboard', absolute: false));
        $this->assertAuthenticatedAs($this->admin);
        $this->assertNotEquals($initialSessionId, session()->getId());
    }

    public function test_logout_invalidates_session(): void
    {
        $this->actingAs($this->admin);

        $response = $this->post('/logout');

        $response->assertRedirect('/');
        $this->assertGuest();
    }

    // ==========================================
    // 3. RATE LIMITING TESTS
    // ==========================================

    public function test_forgot_password_is_rate_limited(): void
    {
        // 6 requests are allowed within 1 minute
        for ($i = 0; $i < 6; $i++) {
            $response = $this->post('/forgot-password', [
                'email' => 'majujaya@example.com',
            ]);
            $this->assertNotEquals(429, $response->getStatusCode());
        }

        // 7th request must be throttled (HTTP 429 Too Many Requests)
        $throttledResponse = $this->post('/forgot-password', [
            'email' => 'majujaya@example.com',
        ]);
        $throttledResponse->assertStatus(429);
    }

    public function test_registration_is_rate_limited(): void
    {
        for ($i = 0; $i < 6; $i++) {
            $response = $this->post('/register', [
                'name' => "User {$i}",
                'email' => "testuser{$i}@example.com",
                'password' => 'password123',
                'password_confirmation' => 'password123',
            ]);
            $this->assertNotEquals(429, $response->getStatusCode());
            \Illuminate\Support\Facades\Auth::logout();
        }

        // 7th request must be throttled
        $throttledResponse = $this->post('/register', [
            'name' => 'User Spam',
            'email' => 'spamuser@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);
        $throttledResponse->assertStatus(429);
    }
}