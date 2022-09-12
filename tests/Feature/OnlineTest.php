<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Test;
use App\Models\Assigncandidate; 
use App\Models\Testtaker;

class OnlineTest extends TestCase
{
    /**
     * Email
     */
    protected $user_email = "hr@admin.com";

    /**
     * Name
     */
    protected $user_name = "Hr Admin";

    /**
     * Gender
     */
    protected $user_gender = "Female";

    /**
     * Date Of Birth
     */
    protected $user_dob = "2010-05-07";

    /**
     * Mobile Number
     */
    protected $user_mobile = "1234567890";
    /**
     * A basic feature test Registation.
     *
     * @return Json
     */

    public function test_registation_api()
    {
        $candidateData = [
            'email' => $this->user_email,
            'name' => $this->user_name,
            'dob' => $this->user_dob,
            'gender' => $this->user_gender,
            'mobile' => $this->user_mobile
        ];

        $user = User::where('email', $this->user_email)->first();
        
        if($user) {
            $test = Test::where('user_id', $user->id)->first();
            
            if($test) {
                $candidate = Assigncandidate::where('test_id', $test->id)
                        ->where('email', $this->user_email)
                        ->where('status', 0)
                        ->orderBy('id', 'desc')
                        ->first();

                $token = $user->createToken('auth_token')->plainTextToken;
                $response = $this->withHeaders(['Authorization' => "Bearer $token"])
                                ->json('POST', '/api/online-test/'.$test->id, $candidateData);

                if($candidate) {
                    $this->assertArrayHasKey('success', $response);
                    $this->assertArrayHasKey('message', $response);
                    $this->assertArrayHasKey('taker', $response);
                    $response->assertStatus(200);
                }
                else {
                    $response->assertStatus(401);
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
     * A basic feature test question by category id.
     *
     * @return Json
     */

    public function test_test_question_api()
    {
        $user = User::where('email', $this->user_email)->first();
        if($user) {
            $test = Test::where('user_id', $user->id)->first();
            if($test) {
                $token = $user->createToken('auth_token')->plainTextToken;
                $response = $this->withHeaders(['Authorization' => "Bearer $token"])
                                ->json('GET', '/api/online-test/'.$test->id.'/question?category_id=1');

                $this->assertArrayHasKey('success', $response);
                $this->assertArrayHasKey('message', $response);
                $this->assertArrayHasKey('question', $response);
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
     * A basic feature test questions.
     *
     * @return Json
     */

    public function test_test_questions_api()
    {
        $user = User::where('email', $this->user_email)->first();
        if($user) {
            $test = Test::where('user_id', $user->id)->first();
            if($test) {
                $token = $user->createToken('auth_token')->plainTextToken;
                $response = $this->withHeaders(['Authorization' => "Bearer $token"])
                                ->json('GET', '/api/online-test/'.$test->id.'/questions?category_id=1');

                $this->assertArrayHasKey('success', $response);
                $this->assertArrayHasKey('message', $response);
                $this->assertArrayHasKey('questions', $response);
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
     * A basic feature test purpose.
     *
     * @return Json
     */

    public function test_test_purpose_api()
    {
        $user = User::where('email', $this->user_email)->first();
        if($user) {
            $token = $user->createToken('auth_token')->plainTextToken;
            $response = $this->withHeaders(['Authorization' => "Bearer $token"])
                                ->json('GET', '/api/testpurpose');

            $this->assertArrayHasKey('success', $response);
            $this->assertArrayHasKey('message', $response);
            $this->assertArrayHasKey('purpose', $response);
            $response->assertStatus(200);
        }
        else {
            $this->assertTrue(true, "Unauthorized");
        }
    }

    /**
     * A basic feature test purpose.
     *
     * @return Json
     */

    public function test_report_pdf_api()
    {
        $user = User::where('email', $this->user_email)->first();
        if($user) {
            $taker = Testtaker::first();
            if($taker){
                $token = $user->createToken('auth_token')->plainTextToken;
                $response = $this->withHeaders(['Authorization' => "Bearer $token"])
                                    ->json('GET', '/api/report/'.$taker->id.'/pdf');
                // dd($response);
                $response->assertStatus(200);
            }
            else {
                $this->assertTrue(true, "");
            }
        }
        else {
            $this->assertTrue(true, "Unauthorized");
        }
    }

    /**
     * get All Test.
     *
     * @return Json
     */

    public function test_get_all_test_api()
    {
        $user = User::where('email', $this->user_email)->first();
        if($user) {
            $token = $user->createToken('auth_token')->plainTextToken;
            $response = $this->withHeaders(['Authorization' => "Bearer $token"])
                                ->json('GET', '/api/all_tests');

            $this->assertArrayHasKey('message', $response);
            $this->assertArrayHasKey('tests', $response);
            $response->assertStatus(200);
        }
        else {
            $this->assertTrue(true, "Unauthorized");
        }
    }

    /**
     * get get criterias.
     *
     * @return Json
     */

    public function test_get_criterias_api()
    {
        $user = User::where('email', $this->user_email)->first();
        if($user) {
            $token = $user->createToken('auth_token')->plainTextToken;
            $response = $this->withHeaders(['Authorization' => "Bearer $token"])
                                ->json('GET', '/api/criterias');

            $this->assertArrayHasKey('message', $response);
            $this->assertArrayHasKey('criterias', $response);
            $response->assertStatus(200);
        }
        else {
            $this->assertTrue(true, "Unauthorized");
        }
    }

    /**
     * get performance type.
     *
     * @return Json
     */

    public function test_get_performance_type_api()
    {
        $user = User::where('email', $this->user_email)->first();
        if($user) {
            $token = $user->createToken('auth_token')->plainTextToken;
            $response = $this->withHeaders(['Authorization' => "Bearer $token"])
                                ->json('GET', '/api/performance-type');

            $this->assertArrayHasKey('message', $response);
            $this->assertArrayHasKey('performance_type', $response);
            $response->assertStatus(200);
        }
        else {
            $this->assertTrue(true, "Unauthorized");
        }
    }

    /**
     * share report.
     *
     * @return Json
     */

    public function test_share_report_api()
    {
        $user = User::where('email', $this->user_email)->first();
        if($user) {

            $takers = Testtaker::first();
            if($takers) {
                $token = $user->createToken('auth_token')->plainTextToken;
                $response = $this->withHeaders(['Authorization' => "Bearer $token"])
                                    ->json('GET', '/api/share-report?key='.$takers->key);

                $response->assertStatus(200);
            }
        }
        else {
            $this->assertTrue(true, "Unauthorized");
        }
    }
}
