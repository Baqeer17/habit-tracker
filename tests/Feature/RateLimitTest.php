<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class RateLimitTest extends TestCase
{
    use DatabaseTransactions;

    public function test_login_endpoint_is_rate_limited()
    {
        // Simulasi 5 request pertama (harus lolos dari Rate Limiter)
        for ($i = 0; $i < 5; $i++) {
            $response = $this->postJson('/api/login', [
                'email' => 'hacker@example.com',
                'password' => 'wrongpassword123',
            ]);
            
            // Memastikan status BUKAN 429 Too Many Requests
            $this->assertNotEquals(429, $response->status());
        }

        // Request ke-6 harus ditolak oleh Rate Limiter (429)
        $response = $this->postJson('/api/login', [
            'email' => 'hacker@example.com',
            'password' => 'wrongpassword123',
        ]);
        
        $response->assertStatus(429);
    }
}
