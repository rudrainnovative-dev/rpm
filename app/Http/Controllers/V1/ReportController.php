<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Testtaker;
use DB;
class ReportController extends Controller
{
    public function index(request $request, $test_id) {

        $takers = Testtaker::where('test_id', $test_id)
                    ->withCount(['answers AS total_marks' => function($query) {
                        $query->select(DB::raw('sum(marks)'));
                    }])
                    ->withCount(['answers AS correct_marks' => function($query) {
                        $query->whereColumn('correct','selected_option');
                        $query->select(DB::raw('sum(marks)'));
                    }])
                    ->paginate(10);

        return response()->json([
                'success' => true,
                'message' => 'taker.',
                'takers' => $takers
            ], 200);
    }
}
