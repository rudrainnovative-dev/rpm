<?php

namespace Tests\Feature\Questions;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Test;

class TestOfQuestionsTest extends TestCase
{
    /*
     * User Email
     */
    protected $user_email = "hr@admin.com";

    /*
     * Test Name
     */
    protected $test_name = "Demo Unit Test";
    /**
     * A basic feature test list.
     *
     * @return void
     */
    public function test_test_list_api()
    {
        $user = User::where('email', $this->user_email)->first();

        if($user) {
            $token = $user->createToken('auth_token')->plainTextToken;
            $response = $this->withHeaders(['Authorization' => "Bearer $token"])
                ->json('GET', '/api/test', [
                    'search' => ''
                ]);

            $this->assertArrayHasKey('success', $response);
            $this->assertArrayHasKey('message', $response);
            $this->assertArrayHasKey('tests', $response);
            $response->assertStatus(200);
        }
        else {
            $this->assertTrue(true, "Unauthorized");
        }
    }

    /**
     * A basic feature test list.
     *
     * @return void
     */
    public function test_test_create_api()
    {
        $testData = [
                    'name'=> $this->test_name,
                    'purpose_id' => 1,
                    'assessment_type' => 'Timed Assessment'
                ];

        $user = User::where('email', $this->user_email)->first();
        if($user) {
            $token = $user->createToken('auth_token')->plainTextToken;
            $response = $this->withHeaders(['Authorization' => "Bearer $token"])
                ->json('POST', '/api/test', $testData);

            $this->assertArrayHasKey('success', $response);
            $this->assertArrayHasKey('message', $response);
            $this->assertArrayHasKey('test_id', $response);
            $response->assertStatus(200);
        }
        else {
            $this->assertTrue(true, "Unauthorized");
        }
    }

    /**
     * A basic feature test show.
     *
     * @return void
     */
    public function test_test_show_api()
    {
        $user = User::where('email', $this->user_email)->first();

        if($user) {
            $test = Test::where('user_id', $user->id)->where('name', $this->test_name)->first();
            $token = $user->createToken('auth_token')->plainTextToken;
            $response = $this->withHeaders(['Authorization' => "Bearer $token"])
                ->json('GET', '/api/test/'.$test->id, [
                    'search' => ''
                ]);

            $this->assertArrayHasKey('success', $response);
            $this->assertArrayHasKey('message', $response);
            $this->assertArrayHasKey('test', $response);
            $this->assertArrayHasKey('test_settings', $response);
            $this->assertArrayHasKey('section_settings', $response);
            $this->assertArrayHasKey('order_settings', $response);
            $response->assertStatus(200);
        }
        else {
            $this->assertTrue(true, "Unauthorized");
        }
    }

    /**
     * A basic feature test Update.
     *
     * @return void
     */
    public function test_test_update_api()
    {
        $testData = [
                    'name'=> $this->test_name,
                    'purpose_id' => 1,
                    'assessment_type' => 'Timed Assessment',
                    'duration' => 30,
                    'start_message' => '',
                    'end_message' => '',
                    'selected_question' => [[
                        'category' => 'Numeric Ability and Data Analysis',
                        'question_id' => 1,
                        'category_id' => 1,
                        'marks' => 1
                    ]],
                    'section_settings' => [
                        1 => 'Section 1',
                        2 => 'Section 2',
                        3 => 'Section 3',
                        4 => 'Section 4'
                    ],
                    'test_settings' => [1,2],
                    'order_settings' => [],
                    '_method'=> "PATCH"
                ];

        $user = User::where('email', $this->user_email)->first();

        if($user) {
            $test = Test::where('user_id', $user->id)->where('name', $this->test_name)->first();
            $token = $user->createToken('auth_token')->plainTextToken;
            $response = $this->withHeaders(['Authorization' => "Bearer $token"])
                ->json('POST', '/api/test/'.$test->id, $testData);

            $this->assertArrayHasKey('success', $response);
            $this->assertArrayHasKey('message', $response);
            $this->assertArrayHasKey('test', $response);
            $response->assertStatus(200);
        }
        else {
            $this->assertTrue(true, "Unauthorized");
        }
    }

    /**
     * A basic feature test list.
     *
     * @return void
     */
    public function test_test_delete_api()
    {
        $user = User::where('email', $this->user_email)->first();
        if($user) {
            $test = Test::where('user_id', $user->id)->where('name', $this->test_name)->first();
            if($test) {
                $token = $user->createToken('auth_token')->plainTextToken;
                $response = $this->withHeaders(['Authorization' => "Bearer $token"])
                    ->json('DELETE', '/api/test/'.$test->id);

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
