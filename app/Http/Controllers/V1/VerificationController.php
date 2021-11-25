<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use App\Models\User;

class VerificationController extends Controller
{

    public function __construct() {
        $this->middleware('signed')->only('verify');
        $this->middleware('throttle:10,1')->only('verify', 'resend');
    }

    public function verify(Request $request) {
        $user = User::find($request->route('id'));

        if(!$user->email_verified_at) {
            \DB::table('users')
                ->where('id', $user->id)
                ->update(['email_verified_at' => Date('Y-m-d H:i:s')]);

            return redirect('/login');
        }
        else {
            return abort(403);
        }
    }
}