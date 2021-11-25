<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Mail;

class AuthController extends Controller
{
    public function register(Request $request) {

        $validator = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|confirmed|min:8|regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\x])(?=.*[!$#%]).*$/|',
        ]);

        $user = User::create([
            'name' => $validator['name'],
            'email' => $validator['email'],
            'password' => Hash::make($validator['password']),
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        $user->sendEmailVerificationNotification();

        return response()->json([
            'success' => true,
            'message' => 'User created successfully. Please check you email for account verification.',
        ], 200);
    }

    public function login(Request $request) {
        
        $validator = $request->validate([
            'email' => 'required|string|email|max:255',
            'password' => 'required|min:8',
        ]);

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json(['message' => 'Invalid login details'], 401);
        }

        $user = User::where('email', $request['email'])->firstOrFail();
        
        if(!$user->email_verified_at) {
           return response()->json(['message' => 'Email not verifyed.'], 401); 
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'user_name' => $user->name,
            'access_token' => $token,
            'token_type' => 'Bearer',
        ], 201);
    }

    public function logout() {
        Auth::logout();
    }

    public function sendResetLinkResponse(Request $request) {
        
        $validator = $request->validate([
            'email' => 'required|string|email|max:255',
        ]);

        $user = User::where('email', $request->email)->first();
        
        if(!empty($user)) {
            $otp = random_int(100000, 999999);
            
            User::where('id', $user->id)->update(['otp'=> $otp]);

            $userUpdate = User::where('id', $user->id)->first();
            
            $email = $userUpdate->email;
            $name = $userUpdate->name;

            $data = ['name' => $userUpdate->name, 'otp' => $userUpdate->otp];
            Mail::send('mail.otp', $data, function($message) use($name, $email) {
                $message->to($email,  $name)
                        ->subject('OTP Send');
            });

            return response()->json([
                'user_id' => $user->id,
                'message' => 'Otp sent in your email.'
            ], 201);  
        }

        return response()->json([
                'error' => "We can't find a user with that e-mail address."
            ], 401);

    }


    public function otpVerification(Request $request) {

        $validator = $request->validate([
            'user_id' => 'required',
            'otp' => 'required'
        ]);

        $user = User::where('id', $request->user_id)
                    ->where('otp', $request->otp)
                    ->first();
        
        if(!empty($user)) {
            return response()->json([
                'user_id' => $user->id,
                'message' => 'Otp verified'
            ], 201); 
        }

        return response()->json([
            'error' => "Invalid OTP."
        ], 401);

    }

    public function resetPassword(Request $request) {
        $validator = $request->validate([
            'user_id' => 'required',
            'password' => 'required|confirmed|min:8|regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\x])(?=.*[!$#%]).*$/|',
        ]);

        $user = User::where('id', $request->user_id)->first();

        if(Hash::check($request->password, $user->password)) {
            return response()->json([
                'error' => 'New password must be different from old password.'
            ], 401);
        }


        User::where('id', $request->user_id)->update(['password' => Hash::make($request->password), 'otp' => '']);

        return response()->json([
                'message' => 'Password updated successfully.'
            ], 201);
    }

}
