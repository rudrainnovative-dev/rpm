<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Registationfield;
use Auth;

class RegisterfieldController extends Controller
{
    public function index() {
        
    }

    public function show($test_id) {

        $registation_fields = Registationfield::where('test_id', $test_id)->where('user_id', Auth::id())->orderBy('id', 'ASC')->get();

        return response()->json([
                'success' => true,
                'message' => 'Test registation fields.',
                'registation_fields' => $registation_fields
            ], 200);
    }

    public function update(Request $request, $test_id) {
        
        if($test_id) {

            Registationfield::where('id', $request->id)
                            ->where('test_id', $request->test_id)
                            ->where('user_id', Auth::id())
                            ->update([
                                'allow' => isset($request->allow)?$request->allow:0,
                                'required' => isset($request->required)?$request->required:0,
                            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Updated Test registation fields.'
            ], 200);

        }

        return response()->json([
                'success' => false,
                'message' => 'Internal Server Errors.'
            ], 400);
        
    }
}
