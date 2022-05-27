<?php

namespace Tests\Feature\Assign;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Assigncandidate;
use App\Models\User;
use App\Models\Assign;

class AssignTest extends TestCase
{
    /**
     * A basic feature assign candidate list.
     *
     * @return AssignCandidate
     */
    public function test_assign_candidate_list()
    {
        $user = User::where('email', 'hr@admin.com')->first();

        if($user) {

            $token = $user->createToken('auth_token')->plainTextToken;
            
            $response = $this->withHeaders(['Authorization' => "Bearer $token"])
                ->json('GET', '/api/assign', [
                    'search' => ''
                ]);

            $response->assertStatus(200);
        }
        else {
            $this->assertTrue(true, "Unauthorized");
        }
    }

    /**
     * A basic feature assign candidate.
     *
     * @return AssignCandidate
     */
    public function test_assign_candidate()
    {
        $user = User::where('email', 'hr@admin.com')->first();

        if($user) {
            $token = $user->createToken('auth_token')->plainTextToken;
            
            $assignData['lists'][0] = [
                    'email' => 'test@gmail.com',
                    'test_id' => 1,
                    'start' => Date('Y-m-d'),
                    'end' => Date('Y-m-d', strtotime('+1 Day')),
                    'type' => 1,
                    'resume' => 0
                ]
            ;

            $response = $this->withHeaders(['Authorization' => "Bearer $token"])
                    ->json('POST', '/api/assign', $assignData);            
            $response->assertStatus(200);
            
        }
        else {
            $this->assertTrue(true, "Unauthorized");
        }
    }

    /**
     * A basic feature assign candidate details.
     *
     * @return QuestionUpdate
     */
    public function test_assign_candidate_details()
    {
        $user = User::where('email', 'hr@admin.com')->first();
        $assign = Assign::first();

        if($user && $assign) {
            $token = $user->createToken('auth_token')->plainTextToken;

            $response = $this->withHeaders(['Authorization' => "Bearer $token"])
                    ->json('GET', '/api/assign/'.$assign->id);
            $response->assertStatus(200, $response->getStatusCode());
            
        }
        else {
            $this->assertTrue(true, "Unauthorized");
        }
    }

    /**
     * A basic feature test assign candidate delete.
     *
     * @return QuestionDelete
     */
    public function test_candidate_delete_from_assign_list()
    {
        $user = User::where('email', 'hr@admin.com')->first();
        $assign = Assigncandidate::where('id', 1)->where('user_id', $user->id)->first();
        if($user && $assign) {
            $token = $user->createToken('auth_token')->plainTextToken;
            $response = $this->withHeaders(['Authorization' => "Bearer $token"])
                        ->json('DELETE', '/api/assign/'.$assign->id);
            $response->assertStatus(200);
        }
        else {
            $this->assertTrue(true, "Unauthorized");
        }
    }
}
