<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Test;

class DashboardController extends Controller
{
    public function index() {
        
    }


    public function recentTest() {
        $tests = Test::with(['purpose','test_questions'])->orderBy('id', 'desc')->limit(5)->get();
    
        return response()->json([
            'success' => true,
            'message' => 'All Question.',
            'tests' => $tests
        ], 200);        
    }

}
