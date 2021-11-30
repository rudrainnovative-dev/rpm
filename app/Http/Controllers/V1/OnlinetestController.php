<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Test;
use App\Models\Testquestion;
use App\Models\Sectionsetting;
use App\Models\Ordersetting;
use App\Models\Testsetting;
use App\Models\Category;
use App\Models\Assigncandidate;
use App\Models\Testtaker;

class OnlinetestController extends Controller
{
    

    public function registation(Request $request, $test_id) {

        if($test_id) {

            request()->validate([
                'email' => 'required|email|max:255',
                'name' => 'required|max:255'
            ]);


            $validate = Assigncandidate::where('test_id', $test_id)
                        ->where('email', $request->email)
                        ->where('status', 0)
                        ->first();

            if(!empty($validate)) {

                $taker = new Testtaker;
                $taker->name = $request->name;
                $taker->dob = $request->dob;
                $taker->email = $request->email;
                $taker->gender = $request->gender;
                $taker->id_card = $request->id_card;
                $taker->mobile = $request->mobile;
                $taker->avatar = $request->avatar;
                $taker->test_id = $test_id;
                $taker->save();

                Assigncandidate::where('test_id', $test_id)
                        ->where('email', $request->email)
                        ->where('status', 0)
                        ->update(['status' => 1]);

                return response()->json([
                    'success' => true,
                    'message' => 'Test Start',
                    'taker' => $taker
                ], 200);
            }

            return response()->json([
                    'success' => false,
                    'message' => 'You are not valid for the test.',
                    'request' => $request->all(),
                    'test' => $test_id
                ], 401);

        }
    }


    public function index(Request $request, $public_id) {
        
        if($public_id) {
            $test = Test::with(['purpose', 'registation_fields'])->where('public_id', $public_id)->first();
            if(!empty($test)) {


                return response()->json([
                    'success' => true,
                    'message' => 'Test',
                    'test' => $test,
                ], 200);
            }
        }

        
    }

}
