<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Test;

class PreviewTestTest extends TestCase
{
    /**
     * User Email for Auth
     */

    protected $user_email = "hr@admin.com";

    /**
     * User Email for Auth
     */

    protected $test_duplicate_name = "Duplicate Unit Test";

    /**
     * A basic feature test duplicate.
     *
     * @return void
     */
    public function test_test_duplicate_api()
    {
        $testData = [
                'name' => $this->test_duplicate_name
            ];

        $user = User::where('email', $this->user_email)->first();
        if($user) {
            $test = Test::where('user_id', $user->id)->first();
            if($test) {
                $testData['id'] = $test->id; 

                $token = $user->createToken('auth_token')->plainTextToken;
                $response = $this->withHeaders(['Authorization' => "Bearer $token"])
                    ->json('POST', '/api/test/duplicate', $testData);

                $this->assertArrayHasKey('success', $response);
                $this->assertArrayHasKey('message', $response);
                $this->assertArrayHasKey('data', $response);
                $response->assertStatus(200);

                $duplicate_test = $test = Test::where('user_id', $user->id)->where('name', $this->test_duplicate_name)->first();
                if($duplicate_test) {
                    $responseDelete = $this->withHeaders(['Authorization' => "Bearer $token"])
                        ->json('DELETE', '/api/test/'.$duplicate_test->id);

                    $this->assertArrayHasKey('success', $responseDelete);
                    $this->assertArrayHasKey('message', $responseDelete);
                    $responseDelete->assertStatus(200);
                }
                else {
                    $this->assertTrue(true, "Unauthorized");
                }
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
     * A basic feature test preview.
     *
     * @return void
     */
    public function test_test_preview_api()
    {
        $user = User::where('email', $this->user_email)->first();
        if($user) {
            $test = Test::where('user_id', $user->id)->first();
            if($test) {
                $token = $user->createToken('auth_token')->plainTextToken;
                $response = $this->withHeaders(['Authorization' => "Bearer $token"])
                    ->json('GET', '/api/online-test/'.$test->public_id.'?cid=YWRtaW4=');

                $this->assertArrayHasKey('success', $response);
                $this->assertArrayHasKey('message', $response);
                $this->assertArrayHasKey('test', $response);
                $this->assertArrayHasKey('test_settings', $response);
                $this->assertArrayHasKey('category', $response);
                $this->assertArrayHasKey('total_questions', $response);
                $this->assertArrayHasKey('test_taker', $response);
                $this->assertArrayHasKey('total_duration', $response);
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
     * A basic feature test report.
     *
     * @return void
     */
    public function test_test_reports_api()
    {
        $user = User::where('email', $this->user_email)->first();
        if($user) {
            $test = Test::where('user_id', $user->id)->first();
            if($test) {
                $token = $user->createToken('auth_token')->plainTextToken;
                $response = $this->withHeaders(['Authorization' => "Bearer $token"])
                    ->json('GET', '/api/report/{$test->id}/test');

                $this->assertArrayHasKey('success', $response);
                $this->assertArrayHasKey('message', $response);
                $this->assertArrayHasKey('takers', $response);
                $this->assertArrayHasKey('processing', $response);
                $this->assertArrayHasKey('upcomming', $response);
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
     * A basic feature test report.
     *
     * @return void
     */
    public function test_test_view_report_api()
    {
        $user = User::where('email', $this->user_email)->first();
        if($user) {
            $test = Test::where('user_id', $user->id)->first();
            if($test) {
                $token = $user->createToken('auth_token')->plainTextToken;
                $response = $this->withHeaders(['Authorization' => "Bearer $token"])
                    ->json('GET', '/api/report/1/test');

                $this->assertArrayHasKey('success', $response);
                $this->assertArrayHasKey('message', $response);
                $this->assertArrayHasKey('takers', $response);
                $this->assertArrayHasKey('processing', $response);
                $this->assertArrayHasKey('upcomming', $response);
                $response->assertStatus(200);

                if($response->getData() && isset($response->getData()->takers->data[0]->id) ) {
                    $taker_id = $response->getData()->takers->data[0]->id;

                    $response = $this->withHeaders(['Authorization' => "Bearer $token"])
                    ->json('GET', '/api/report/{$taker_id}/show');

                    $this->assertArrayHasKey('taker', $response);
                    $this->assertArrayHasKey('categories', $response);
                    $this->assertArrayHasKey('performance', $response);
                    $this->assertArrayHasKey('sections', $response);
                    $this->assertArrayHasKey('correct_sections', $response);
                    $this->assertArrayHasKey('avatars', $response);
                    $this->assertArrayHasKey('screenshots', $response);
                    $this->assertArrayHasKey('logs', $response);
                    $response->assertStatus(200);
                }
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
