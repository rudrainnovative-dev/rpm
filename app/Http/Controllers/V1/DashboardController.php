<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Test;
use App\Models\Assigncandidate;
use Carbon\Carbon;
class DashboardController extends Controller
{
    public function index() {
        $tests = Test::with(['purpose','test_questions'])->orderBy('id', 'desc')->limit(5)->get();

        $upcomming_test = Assigncandidate::with(['test'])
                    ->whereDate('end', '>', Carbon::now())
                    ->orderBy('id', 'desc')
                    ->limit(5)
                    ->get();

        return response()->json([
            'success' => true,
            'message' => 'All Question.',
            'tests' => $tests,
            'upcomming_test' => $upcomming_test
        ], 200);
    }

}
