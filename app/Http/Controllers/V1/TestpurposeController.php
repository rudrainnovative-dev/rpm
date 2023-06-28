<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Testpurpose;

class TestpurposeController extends Controller
{
    public function index() {
        $purpose = Testpurpose::orderBy('id', 'asc')->get();
        
        return response()->json([
            'success' => true,
            'message' => 'All Purpose.',
            'purpose' => $purpose
        ], 200);
    }
}
