<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Test;
use App\Models\Assigncandidate;
use App\Models\Testtaker;
use Carbon\Carbon;
use Auth;

class DashboardController extends Controller
{
    public function index(request $request) {
        $user_id = Auth::id();
        $tests = Test::with(['purpose','test_questions'])
                ->where('user_id', $user_id)
                ->orderBy('id', 'desc')
                ->limit(5)
                ->get();

        $upcomming_test = Assigncandidate::with(['test'])
                    ->where('user_id', $user_id)
                    ->where('status', 0)
                    ->whereDate('end', '>', Carbon::now())
                    ->orderBy('id', 'desc')
                    ->limit(5)
                    ->get();

        $completed_test = Testtaker::where('user_id', $user_id)
                            ->orderBy('id', 'desc')
                            ->limit(5)
                            ->get();

        return response()->json([
            'success' => true,
            'message' => 'All Question.',
            'tests' => $tests,
            'upcomming_test' => $upcomming_test,
            'completed_test' => $completed_test,
            'user' => $request->user()
        ], 200);
    }

}
