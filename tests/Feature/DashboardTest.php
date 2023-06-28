<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class DashboardTest extends TestCase
{
    protected $user_email = "hr@admin.com";
    
    /**
     * User Dashboard.
     *
     * @return void
     */
    public function test_dashboard_api()
    {
        
        $user = User::where('email', $this->user_email)->first();
        if($user) {
            $token = $user->createToken('auth_token')->plainTextToken;
            $response = $this->withHeaders(['Authorization' => "Bearer $token"])
                ->json('GET', '/api/dashboard');

            $this->assertArrayHasKey('success', $response);
            $this->assertArrayHasKey('message', $response);
            $this->assertArrayHasKey('tests', $response);
            $this->assertArrayHasKey('upcomming_test', $response);
            $this->assertArrayHasKey('completed_test', $response);
            $this->assertArrayHasKey('user', $response);
            $response->assertStatus(200);
        }
        else {
            $this->assertTrue(true, "Unauthorized");
        }
    }
}
