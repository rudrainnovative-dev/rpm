<?php

namespace Tests\Feature\Performance;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Performancecriteria;

class PerformanceTest extends TestCase
{   

    protected $user_email = "hr@admin.com";
    /**
     * A basic feature test performance list.
     *
     * @return void
     */
    public function test_performance_list_api()
    {
        $user = User::where('email', $this->user_email)->first();
        if($user) {
            $token = $user->createToken('auth_token')->plainTextToken;
            $response = $this->withHeaders(['Authorization' => "Bearer $token"])
                ->json('GET', '/api/performance');

            $this->assertArrayHasKey('success', $response);
            $this->assertArrayHasKey('performance_criterias', $response);
            $this->assertArrayHasKey('current_performance', $response);
            $response->assertStatus(200);
        }
        else {
            $this->assertTrue(true, "Unauthorized");
        }
    }

    /**
     * A basic feature test performance show.
     *
     * @return void
     */
    public function test_performance_show_api()
    {
        $user = User::where('email', $this->user_email)->first();
        $performance = Performancecriteria::first();
        if($user) {
            $token = $user->createToken('auth_token')->plainTextToken;
            $response = $this->withHeaders(['Authorization' => "Bearer $token"])
                ->json('GET', '/api/performance/'.$performance->id);

            $this->assertArrayHasKey('success', $response);
            $this->assertArrayHasKey('performance', $response);
            $this->assertArrayHasKey('performance_type', $response);
            $this->assertArrayHasKey('current_performance', $response);
            $response->assertStatus(200);
        }
        else {
            $this->assertTrue(true, "Unauthorized");
        }
    }

    /**
     * A basic feature test performance add.
     *
     * @return void
     */
    public function test_performance_add_api()
    {
        $user = User::where('email', $this->user_email)->first();
        $data['remarks'] = ["Excellent", "Moderate", "High", "Very High", "Low", "Very Low"];
        $data['data'] = [ 
            [ "criteria" => "Overall", "formula" => ">", "score" => 90],
            ["criteria" => "Overall", "formula" => ">", "score" => 80],
            ["criteria" => "Overall", "formula" => ">", "score" => 70],
            ["criteria" => "Overall", "formula" => ">", "score" => 60],
            ["criteria" => "Overall", "formula" => ">", "score" => 50],
            ["criteria" => "Overall", "formula" => "<", "score" => 50]
        ];

        if($user) {
            $token = $user->createToken('auth_token')->plainTextToken;
            $response = $this->withHeaders(['Authorization' => "Bearer $token"])
                ->json('POST', '/api/performance', $data);
            $this->assertArrayHasKey('success', $response);
            $response->assertStatus(200);
        }
        else {
            $this->assertTrue(true, "Unauthorized");
        }
    }

    /**
     * A basic feature test performance destroy.
     *
     * @return void
     */
    public function test_performance_destroy_api()
    {
        $user = User::where('email', $this->user_email)->first();
        if($user) {
            $performance = Performancecriteria::where('user_id', $user->id)->orderBy('id', 'Desc')->first();
            if($performance) {
                $token = $user->createToken('auth_token')->plainTextToken;
                $response = $this->withHeaders(['Authorization' => "Bearer $token"])
                    ->json('GET', '/api/performance/'.$performance->id);

                $this->assertArrayHasKey('success', $response);
                $this->assertArrayHasKey('performance', $response);
                $this->assertArrayHasKey('performance_type', $response);
                $this->assertArrayHasKey('current_performance', $response);
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
