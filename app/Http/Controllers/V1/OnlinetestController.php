<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Test;

class OnlinetestController extends Controller
{
    
    public function index(Request $request, $public_id) {
        
        if($public_id) {

            $test = Test::where('public_id', $public_id)
                    ->with(['purpose'])
                    ->first();

            return response()->json([
                'success' => true,
                'message' => 'Test',
                'test' => $test
            ], 200);
        }
        
        
    }

}
