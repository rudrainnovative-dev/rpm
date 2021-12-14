<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Testtaker;
use App\Models\Testtakeranswer;
use App\Models\Performancecriteria;
use App\Models\Category;
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

    public function show(request $request, $taker_id) {

        $categories = Category::orderBy('id', 'asc')->pluck('name', 'id');

        $taker = Testtaker::where('id', $taker_id)
                    ->with(['answers_by_section' => function($query) {
                        $query->whereColumn('correct','selected_option');
                        $query->groupBy('category_id');
                        $query->select('taker_id','category_id', 'category', DB::Raw('sum(marks) as marks'));
                    }])
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


        return response()->json([
                'success' => true,
                'message' => 'taker.',
                'taker' => $taker,
                'categories' => $categories,
                'performance' => $performance,
            ], 200);
    }
}
