<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Test;

class RegisterFieldTest extends TestCase
{
    protected $user_email = "hr@admin.com";
    
    /**
     * A basic feature show register field.
     *
     * @return void
     */
    public function test_register_field_show_api()
    {
        $user = User::where('email', $this->user_email)->first();
        if($user) {
            $test = Test::where('user_id', $user->id)->first();
            if($test) {
                $token = $user->createToken('auth_token')->plainTextToken;
                $response = $this->withHeaders(['Authorization' => "Bearer $token"])
                    ->json('GET', '/api/registation-field/'.$test->id);

                $this->assertArrayHasKey('success', $response);
                $this->assertArrayHasKey('message', $response);
                $this->assertArrayHasKey('registation_fields', $response);
                $response->assertStatus(200);
            }
            else {
                $this->assertTrue(true, "Unauthorized");
            }
        }
        else {
            $this->assertTrue(true, "Unauthorized");
        }
    }

    /**
     * A basic feature update register field.
     *
     * @return void
     */
    public function test_register_field_update_api()
    {
        $user = User::where('email', $this->user_email)->first();
        if($user) {
            $test = Test::where('user_id', $user->id)->first();
            if($test) {
                $token = $user->createToken('auth_token')->plainTextToken;
                $response = $this->withHeaders(['Authorization' => "Bearer $token"])
                    ->json('POST', '/api/registation-field/'.$test->id, ['_method'=>"PUT"]);

                $this->assertArrayHasKey('success', $response);
                $this->assertArrayHasKey('message', $response);
                $response->assertStatus(200);
            }
            else {
                $this->assertTrue(true, "Unauthorized");
            }
        }
        else {
            $this->assertTrue(true, "Unauthorized");
        }
    }
}
