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
use PDF;
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
                    ->where('status', 2)
                    ->paginate(10);

        $takersProcess = Testtaker::where('test_id', $test_id)
                    ->withCount(['answers AS total_marks' => function($query) {
                        $query->select(DB::raw('sum(marks)'));
                    }])
                    ->withCount(['answers AS correct_marks' => function($query) {
                        $query->whereColumn('correct','selected_option');
                        $query->select(DB::raw('sum(marks)'));
                    }])
                    ->where('status', 1)
                    ->paginate(10);

        return response()->json([
                'success' => true,
                'message' => 'taker.',
                'takers' => $takers,
                'processing' => $takersProcess
            ], 200);
    }

    public function show(request $request, $taker_id) {

        $categories = Category::orderBy('id', 'asc')->pluck('name', 'id');
        $taker = Testtaker::where('id', $taker_id)
                    ->withCount(['answers AS total_marks' => function($query) {
                        $query->select(DB::raw('sum(marks)'));
                    }])
                    ->withCount(['answers AS correct_marks' => function($query) {
                        $query->whereColumn('correct','selected_option');
                        $query->select(DB::raw('sum(marks)'));
                    }])
                    ->first();

        $get_performance = Performancecriteria::where('user_id', $taker->user_id)
                        ->with(['options', 'options.op_criteria'])
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

        return response()->json([
                'success' => true,
                'message' => 'taker.',
                'taker' => $taker,
                'categories' => $categories,
                'performance' => $performance,
                'sections' => $sections,
                'correct_sections' => $correct_sections,
                'avatars' => $avatars,
                'screenshots' => $screenshots,
                'logs' => $logs
            ], 200);
    }

    public function pdf(request $request, $taker_id) {

        $categories = Category::orderBy('id', 'asc')->pluck('name', 'id');
        $taker = Testtaker::where('id', $taker_id)
                    ->withCount(['answers AS total_marks' => function($query) {
                        $query->select(DB::raw('sum(marks)'));
                    }])
                    ->withCount(['answers AS correct_marks' => function($query) {
                        $query->whereColumn('correct','selected_option');
                        $query->select(DB::raw('sum(marks)'));
                    }])
                    ->first();
        if(!empty($taker)) {
            $get_performance = Performancecriteria::where('user_id', $taker->user_id)
                            ->with(['options', 'options.op_criteria'])
                            ->orderBy('id', 'desc')
                            ->first();
            $sections = Testtakeranswer::where('taker_id', $taker->id)
                                ->select('category_id', DB::Raw('sum(marks) as total_marks'))
                                ->groupBy('category_id')
                                ->pluck('total_marks', 'category_id');
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
            $data_array = [
                    'taker' => $taker,
                    'categories' => $categories,
                    'performance' => $performance,
                    'sections' => $sections,
                    'correct_sections' => $correct_sections,
                    'avatars' => $avatars,
                    'screenshots' => $screenshots
                ];

            $file = 'report_'.$taker->id.'.pdf';
            $pdf = PDF::loadView('report', $data_array);
            return $pdf->download();

        }

    }
}
