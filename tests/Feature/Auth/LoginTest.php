<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class LoginTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    private $test_email = 'rudra_unit_test@mailinator.com';
    private $test_password = 'Hello@112#';
    private $test_otp = '56123';

    //Register API
    public function test_user_register_api(){       
        $user = User::where('email', $this->test_email)->first();        
        if( $user ){
            $user->delete();
        }

        $arr = [
            'name' => 'Rudra Test-automations',
            'email' => $this->test_email,
            'password' => $this->test_password,
            'password_confirmation' => $this->test_password
        ];
        $response = $this->post('/api/register', $arr);        
        
        //Case1 - Create user check if providing correct response
        $response->assertStatus(200, "Some error in response code of Register API")
        ->assertJsonPath('success', true);        
    }

    // Login API
    public function test_user_login_api(){
        $user = User::factory()->count(1)->create();        
        if( $user && isset($user[0]) ){
            $id = $user[0]->id;
            $email = $user[0]->email;
            $arr = [
                'email' => $user[0]->email,
                'password' => 'password'
            ];
            $response = $this->post('/api/login', $arr);
            //Case1
            $response->assertStatus(201, "API Response status code is something else"); // check status code    

            //Case2
            $this->assertArrayHasKey('access_token', $response, "access_token key is not available"); // check if response has access_token
            
            // Case3 - With wrong email or password
            $arr = [
                'email' => $user[0]->email,
                'password' => 'password1'
            ];
            $response = $this->post('/api/login', $arr);
            $response->assertStatus(401, "Except 401 as either username or password wrong");
            
            User::where('id', $id)->delete();
        }
        else{
            $this->assertTrue(0, "User::factroy unable to create user new user");
        }
    }

    // Forgot password API
    public function test_forgot_password_api(){

        $response = $this->post('/api/password/email', ['email' => $this->test_email]);
        //Case1 - Create user check if providing correct response
        $response->assertStatus(201);
        $this->assertArrayHasKey('user_id', $response);
        
        $response_error = $this->post('/api/password/email', ['email' => $this->test_email.'1']);
        $response_error->assertStatus(401);
        $this->assertArrayHasKey('error', $response_error);
    }

    // Verify OTP API
    public function test_otp_verify_api(){
        $user = User::where('email', $this->test_email)->first();
        if( $user ){            
            $user_id = $user->id;
            $user->update(['otp' => $this->test_otp]);
            // Check If OTP is right
            $response = $this->post('/api/otp/verify', ['user_id' => $user_id, 'otp' =>$this->test_otp]);
            $response->assertStatus(201)->assertJsonPath('user_id', $user_id);

            // Check if OTP is wrong
            $response_error = $this->post('/api/otp/verify', ['user_id' => $user_id, 'otp' =>$this->test_otp.'1']);
            $response_error->assertStatus(401);
            $this->assertArrayHasKey('error', $response_error);
        }
        else{
            $this->assertTrue(0);
        }
    }

    // Password reset API (after otp verification)
    public function test_password_reset_api(){
        $user = User::where('email', $this->test_email)->first();
        if( $user ){
            $user_id = $user->id;            
            $data = ['user_id' => $user_id, 'password' => $this->test_password, 'password_confirmation' => $this->test_password];
            
            // Check by sending same as old password
            $response_error = $this->post('/api/password/reset', $data); 
            $response_error->assertStatus(401);
            $this->assertArrayHasKey('error', $response_error);

            // Check password reset success
            $this->test_password = 'Hello@1234567#';
            $data['password'] = $this->test_password;
            $data['password_confirmation'] = $this->test_password;
            $response = $this->post('/api/password/reset', $data);            
            $response->assertStatus(201);
            $this->assertArrayHasKey('message', $response);
        }
        else{
            $this->assertTrue(0);
        }
    }

    // Remove Test user at the end
    public function test_remove_test_user(){        
        $user = User::where('email', $this->test_email)->first();        
        if( $user ){
            $user->delete();
        }
        $this->assertTrue(true);
    }

}
