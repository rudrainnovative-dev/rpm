<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Test;
use App\Models\Testquestion;
use App\Models\Sectionsetting;
use App\Models\Ordersetting;
use App\Models\Testsetting;
use App\Models\Category;
use App\Models\Assigncandidate;
use App\Models\Testtaker;
use App\Models\Testtakeranswer;
use App\Models\Testtakersnap;
use App\Models\Testtakerscreenshot;
use App\Models\Performancecriteria;
use DB;
use File;

class OnlinetestController extends Controller
{
    public function index(Request $request, $public_id) {
        
        if($public_id) {

            $test = Test::with(['purpose', 'registation_fields'])->where('public_id', $public_id)->first();

            if(!empty($test)) {

                $test_settings = Testsetting::where('test_id', $test->id)->pluck('criteria_id');
                $order_settings = Ordersetting::where('test_id', $test->id)->pluck('order', 'section_id');
                $section_settings = Sectionsetting::where('test_id', $test->id)->pluck('instruction', 'section_id');
                $test_questions = Testquestion::with('category')
                                ->where('test_id', $test->id)
                                ->groupBy('category_id')
                                ->select(DB::raw('COUNT(id) as total'), 'category_id')
                                ->get();

                $total_questions = Testquestion::where('test_id', $test->id)->count();

                $categories = [];
                $i = 0;
                foreach($test_questions as $question) {
                    $categories[$i]['id'] = $question->category_id;
                    $categories[$i]['name'] = $question->category->name;
                    $categories[$i]['info'] = isset($section_settings[$question->category_id])?$section_settings[$question->category_id]:'';
                    $categories[$i]['order'] = isset($order_settings[$question->category_id])?$order_settings[$question->category_id]:0;
                    $i++;
                }

                $columns = array_column($categories, 'order');
                array_multisort($columns, SORT_ASC, $categories);

                return response()->json([
                    'success' => true,
                    'message' => 'Online Test',
                    'test' => $test,
                    'test_settings' => $test_settings,
                    'category' => $categories,
                    'total_questions' => $total_questions
                ], 200);
            }
            else {
                return response()->json([
                    'success' => false,
                    'error' => 'This test has been deactivated. Please contact your administrator.',
                ], 400);
            }
        }
        else {
            return response()->json([
                'success' => false,
                'error' => 'This test has been deactivated. Please contact your administrator.',
            ], 400);
        }

    }


    public function registation(Request $request, $test_id) {

        if($test_id) {

            request()->validate([
                'email' => 'required|email|max:255',
                'name' => 'required|max:255'
            ]);

            $test = Test::where('id', $test_id)->with('test_questions')->first();

            $validate = Assigncandidate::where('test_id', $test_id)
                        ->where('email', $request->email)
                        ->where('status', 0)
                        ->first();

            if(!empty($validate) && !empty($test)) {

                $taker = new Testtaker;
                $taker->name = $request->name;
                $taker->email = $request->email;
                $taker->dob = $request->dob;
                $taker->gender = $request->gender;
                $taker->mobile = $request->mobile;
                $taker->avatar = $request->avatar;
                $taker->id_card = $request->id_card;
                $taker->test_id = $test_id;
                $taker->test_name = $test->name;
                $taker->total_questions = count($test->test_questions);
                $taker->user_id = $validate->user_id;
                $taker->status = 1;
                $taker->save();

                Assigncandidate::where('test_id', $test_id)
                        ->where('email', $request->email)
                        ->where('status', 0)
                        ->update(['status' => 1]);

                return response()->json([
                    'success' => true,
                    'message' => 'Registation successfully.',
                    'taker' => $taker
                ], 200);
            }

            return response()->json([
                    'success' => false,
                    'error' => 'This email is not authorized to take test using this test link.',
                    'request' => $request->all(),
                    'test' => $test_id
                ], 401);

        }
    }


    public function question(Request $request, $test_id) {
        
        if($test_id) {
            $question = Testquestion::with('question', 'question.options', 'category')
                        ->where('test_id', $test_id)
                        ->where('category_id', $request->category_id)
                        ->paginate(1);

            return response()->json([
                    'success' => true,
                    'message' => 'Question',
                    'question' => $question,
                ], 200);
        }
    }

    public function questions(Request $request, $test_id) {
        if($test_id) {
            $questions = Testquestion::with('question', 'question.options', 'category')
                        ->where('test_id', $test_id)
                        ->where('category_id', $request->category_id)
                        ->get();

            return response()->json([
                    'success' => true,
                    'message' => 'Questions',
                    'questions' => $questions,
                ], 200);
        }
    }
    
    public function answered(Request $request) {
        if(isset($request->selected) && isset($request->question_id)) {
            $taker_id = 0;
            if(isset($request->taker_id)) {
                $taker_id = $request->taker_id; 
            }
            $options = $request->options;
            $answered = $request->selected + 1;
            $create_update = Testtakeranswer::updateOrCreate([
                                'taker_id' => $taker_id,
                                'question_id' => $request->question_id,
                                'question' => $request->question,
                                'category' => $request->category,
                                'category_id' => $request->category_id,
                                'option_one' => $options[0]['option'],
                                'option_two' => $options[1]['option'],
                                'option_three' => $options[2]['option'],
                                'option_four' => $options[3]['option'],
                                'correct' => $request->correct,
                                'marks' => $request->marks,
                                'test_id' => $request->test_id,
                            ], [
                                'selected_option' => $answered
                            ]
                        );

            return response()->json([
                'success' => true,
                'message' => 'Questions',
            ], 200);
        }
    }

    public function taker_update(request $request) {
        
        if($request->taker_email && $request->id) {
            Assigncandidate::where('test_id', $request->id)
                ->where('email', $request->taker_email)
                ->where('status', 1)
                ->update(['status' => 0]);

            Testtaker::where('id', $request->taker_id)->delete();

            return response()->json([
                'success' => true,
                'message' => 'success',
            ], 200);
        }
    }

    public function taker_snap(request $request) {

        if($request->taker_id && $request->id && $request->snap) {

            Testtakersnap::insert([
                'taker_id' => $request->taker_id,
                'test_id' => $request->id,
                'snap' => $request->snap,
                'created_at' => Date('Y-m-d H:i:s'),
                'updated_at' => Date('Y-m-d H:i:s')
            ]);

            return response()->json([
                'success' => true,
                'message' => 'save face',
            ], 200);
        }
    }

    public function taker_screenshot(request $request) {

        if($request->taker_id && $request->id && $request->screensnap) {

            Testtakerscreenshot::insert([
                'taker_id' => $request->taker_id,
                'test_id' => $request->id,
                'snap' => $request->screensnap,
                'created_at' => Date('Y-m-d H:i:s'),
                'updated_at' => Date('Y-m-d H:i:s')
            ]);

            return response()->json([
                'success' => true,
                'message' => 'save screen',
            ], 200);

        }
    }

    public function updateStatus(Request $request, $taker_id) {
        if($taker_id && $request->taker_id == $taker_id) {
            Testtaker::where('id', $taker_id)
                    ->update([
                        'status'=>2,
                        'updated_at' => Date('Y-m-d H:i:s')
                    ]);
        }
    }


    public function report($taker_id) {
        if($taker_id) {
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

            return response()->json([
                    'success' => true,
                    'message' => 'taker.',
                    'taker' => $taker,
                    'categories' => $categories,
                    'performance' => $performance,
                    'sections' => $sections,
                    'correct_sections' => $correct_sections,
                ], 200);
        }
    }

}
