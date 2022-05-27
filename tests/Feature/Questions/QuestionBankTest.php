<?php

namespace Tests\Feature\Questions;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Questionbank;
use App\Models\User;
use App\Models\Test;

class QuestionBankTest extends TestCase
{
    /**
     * A basic feature test question list.
     *
     * @return QuestionList
     */
    public function test_question_list()
    {
        $user = User::where('email', 'hr@admin.com')->first();

        if($user) {

            $token = $user->createToken('auth_token')->plainTextToken;
            
            $response = $this->withHeaders(['Authorization' => "Bearer $token"])
                ->json('GET', '/api/question', [
                    'search' => ''
                ]);

            $response->assertStatus(200);
        }
        else {
            $this->assertTrue(true, "Unauthorized");
        }
    }

    /**
     * A basic feature test question add.
     *
     * @return QuestionAdd
     */
    public function test_question_add()
    {
        $user = User::where('email', 'hr@admin.com')->first();

        if($user) {
            $token = $user->createToken('auth_token')->plainTextToken;
            
            $questionData = [
                'category_id' => '1',
                'title' => 'Test Question',
                'marks' => 1,
                'correct' => 2,
                'answers_justification' => '',
                'options' => ['Option A', 'Option B', 'Option C', 'Option D']
            ];

            if(!Questionbank::where('title', 'Test Question')->exists()) {
                $response = $this->withHeaders(['Authorization' => "Bearer $token"])
                        ->json('POST', '/api/question', $questionData);            
                $response->assertStatus(200);
            }
            else {
                 $response = $this->withHeaders(['Authorization' => "Bearer $token"])
                        ->json('POST', '/api/question', $questionData);
                $response->assertStatus(400);
            }
        }
        else {
            $this->assertTrue(true, "Unauthorized");
        }
    }

    /**
     * A basic feature test question update.
     *
     * @return QuestionUpdate
     */
    public function test_question_update()
    {
        $user = User::where('email', 'hr@admin.com')->first();

        if($user) {
            $token = $user->createToken('auth_token')->plainTextToken;
            $questionData = [
                'category_id' => 1,
                'title' => 'Test Question',
                'marks' => 2,
                'correct' => 3,
                'answers_justification' => 'test justification',
                'option_ids' => [],
                "_method" => "PUT"
            ];

            if(Questionbank::where('title', 'Test Question')->exists()) {
                
                $question = Questionbank::where('title', 'Test Question')->first();
                $response = $this->withHeaders(['Authorization' => "Bearer $token"])
                        ->json('POST', '/api/question/'.$question->id, $questionData);
                $response->assertStatus(200, $response->getStatusCode());
            }
        }
        else {
            $this->assertTrue(true, "Unauthorized");
        }
    }

    /**
     * A basic feature test question delete.
     *
     * @return QuestionDelete
     */
    public function test_question_delete()
    {
        $user = User::where('email', 'hr@admin.com')->first();
        if($user) {
            $token = $user->createToken('auth_token')->plainTextToken;

            if(Questionbank::where('title', 'Test Question')->exists()) {
                
                $question = Questionbank::where('title', 'Test Question')->first();
                $response = $this->withHeaders(['Authorization' => "Bearer $token"])
                        ->json('DELETE', '/api/question/'.$question->id);

                $response->assertStatus(200);
            }
        }
        else {
            $this->assertTrue(true, "Unauthorized");
        }
    }

    /**
     * A basic feature get test question.
     *
     * @return TestQuestion
     */
    public function test_test_question()
    {   
        $user = User::where('email', 'hr@admin.com')->first();
        $test = Test::where('user_id', $user->id)->first();
        if($user && $test) {

            $token = $user->createToken('auth_token')->plainTextToken;
            $response = $this->withHeaders(['Authorization' => "Bearer $token"])
                        ->json('GET', '/api/question/test/'.$test->id);

            $response->assertStatus(200);
            
        }
        else {
            $this->assertTrue(true, "Unauthorized");
        }
    }
}
