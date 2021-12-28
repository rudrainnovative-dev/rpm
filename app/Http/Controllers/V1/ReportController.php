<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Testtaker;
use App\Models\Testtakeranswer;
use App\Models\Performancecriteria;
use App\Models\Category;
use App\Models\Testquestion;
use App\Models\Testtakersnap;
use App\Models\Testtakerscreenshot;
use App\Models\Assigncandidate;
use PDF;
use DB;
use App\Models\Test;
use Auth;
use Carbon\Carbon;
class ReportController extends Controller
{
    public function index(request $request, $test_id) {
        
        $user_id = Auth::id();

        $takers = Testtaker::where('test_id', $test_id)
                    ->withCount(['answers AS correct_marks' => function($query) {
                        $query->whereColumn('correct','selected_option');
                        $query->select(DB::raw('sum(marks)'));
                    }])
                    ->where('user_id', $user_id)
                    ->where('status', 2)
                    ->orderBy('id', 'desc')
                    ->paginate(10);

        $takersProcess = Testtaker::where('test_id', $test_id)
                    ->withCount(['answers AS correct_marks' => function($query) {
                        $query->whereColumn('correct','selected_option');
                        $query->select(DB::raw('sum(marks)'));
                    }])
                    ->where('user_id', $user_id)
                    ->where('status', 1)
                    ->orderBy('id', 'desc')
                    ->paginate(10);

        $upcomming = Assigncandidate::with(['test'])
                    ->where('user_id', $user_id)
                    ->where('status', 0)
                    ->whereDate('end', '>', Carbon::now())
                    ->orderBy('id', 'desc')
                    ->paginate(10);

        return response()->json([
                'success' => true,
                'message' => 'taker.',
                'takers' => $takers,
                'processing' => $takersProcess,
                'upcomming' => $upcomming
            ], 200);
    }

    public function report_data($taker_id) {


        $categories = Category::orderBy('id', 'asc')->pluck('name', 'id');
        $taker = Testtaker::where('id', $taker_id)
                    ->withCount(['answers AS correct_marks' => function($query) {
                        $query->whereColumn('correct','selected_option');
                        $query->select(DB::raw('sum(marks)'));
                    }])
                    ->first();

        $get_performance = Performancecriteria::where('user_id', $taker->user_id)
                        ->with(['options', 'options.op_criteria'])
                        ->whereDate('created_at', '<', $taker->created_at)
                        ->orderBy('id', 'desc')
                        ->first();

        $sections = Testtakeranswer::where('taker_id', $taker->id)
                            ->select('category_id', DB::Raw('sum(marks) as total_marks'))
                            ->groupBy('category_id')
                            ->pluck('total_marks', 'category_id');

        $logs = Testtakeranswer::where('taker_id', $taker->id)
                            ->select('category_id', 'created_at')
                            ->groupBy('category_id')
                            ->orderBy('created_at', 'asc')
                            ->pluck('created_at', 'category_id');

        $correct_sections = Testtakeranswer::where('taker_id', $taker->id)
                            ->whereColumn('correct','selected_option')
                            ->select('category_id', DB::Raw('sum(marks) as total_marks'))
                            ->groupBy('category_id')
                            ->pluck('total_marks', 'category_id');

        $performance = [];
        if(isset($get_performance->options)) {
            foreach($get_performance->options as $option) {

                $performance[$option->op_criteria->id]['name'] = $option->op_criteria->name;

                if($option->formula == '=') {
                    $performance[$option->op_criteria->id]['min'] = $option->score;
                    $performance[$option->op_criteria->id]['max'] = $option->score;
                }

                if($option->formula == '<') {
                    $performance[$option->op_criteria->id]['min'] = 0;
                    $performance[$option->op_criteria->id]['max'] = $option->score;
                }

                if($option->formula == '>') {
                    $performance[$option->op_criteria->id]['min'] = $option->score;
                    $performance[$option->op_criteria->id]['max'] = 100;
                }

                $performance[$option->op_criteria->id]['criteria'] = $option->criteria;
            }
        }

        $performance = array_reverse($performance);
        $avatars = Testtakersnap::where('taker_id', $taker->id)->where('test_id', $taker->test_id)->get();
        $screenshots = Testtakerscreenshot::where('taker_id', $taker->id)->where('test_id', $taker->test_id)->get();

        return [
                'taker' => $taker,
                'categories' => $categories,
                'performance' => $performance,
                'sections' => $sections,
                'correct_sections' => $correct_sections,
                'avatars' => $avatars,
                'screenshots' => $screenshots,
                'logs' => $logs
            ];

    }
    public function show(request $request, $taker_id) {
        if($taker_id) {
            $data['success'] = true;
            $data['message'] = 'taker.';
            $data = $this->report_data($taker_id);
        
            return response()->json($data, 200);
        }
        else {
            return response()->json([
                'success' => false,
                'error' => 'taker not exist'
            ], 400);
        }
    }

    public function pdf(request $request, $taker_id) {
        if($taker_id) {
            $data_array = $this->report_data($taker_id);
            $file = 'report_'.$taker_id.'.pdf';
            $pdf = PDF::loadView('report', $data_array);
            return $pdf->download();
        }
        else {
            return response()->json([
                'success' => false,
                'error' => 'taker not exist'
            ], 400);
        }
    }

    public function sendEmailPDF(request $request) {
        
        $data['lists'] = $request->all();

        $job = (new \App\Jobs\SendEmailPDF($data))
            ->delay(now()->addSeconds(2)); 

        $this->dispatch($job);
        
        return response()->json([
            'success' => true,
            'message' => 'Performance report send to candidate.',
        ], 200);
    }
}
