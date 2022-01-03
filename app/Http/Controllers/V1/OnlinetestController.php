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
use Carbon\Carbon;
use App\Http\Controllers\V1\ReportController;

class OnlinetestController extends Controller
{
    public function index(Request $request, $public_id) {

        if($request->cid && $request->cid != 'YWRtaW4=') {
            $cid = base64_decode($request->cid);
            $candidate = Assigncandidate::where('id', $cid)->first();
            if(empty($candidate)) {
                return response()->json([
                    'success' => false,
                    'error' => 'You are not allowed to take test. Please contact your administrator for assistance.',
                ], 400);
            }

            if($candidate->start > Carbon::now() || $candidate->end < Carbon::now()) {
                return response()->json([
                    'success' => false,
                    'error' => 'Since this test has expired you cannot start a new test.',
                ], 400);
            }

            if($candidate->status == 1) {
                return response()->json([
                    'success' => false,
                    'error' => 'Test is already finished by You. So you cannot start a new test',
                ], 400);
            }
        }

        if($public_id) {
            $test = Test::with(['purpose', 'registation_fields'])
                    ->where('public_id', $public_id)
                    ->first();

            if(!empty($test)) {
                $test_settings = Testsetting::where('test_id', $test->id)->pluck('criteria_id');
                if (!in_array(10, $test_settings->toArray()) && $request->cid != 'admin') {
                    $checkTest = Testtaker::where('test_id', $test->id)
                                ->whereBetween('created_at', [Carbon::now()->subMinute(30), Carbon::now()])
                                ->where('status', 1)    
                                ->first();
                            
                    if(!empty($checkTest)) {
                        return response()->json([
                            'success' => false,
                            'error' => 'Test already accessed by other candidate at the time. Please try again after few moment.',
                        ], 400);
                    }
                }

                $order_settings = Ordersetting::where('test_id', $test->id)->pluck('order', 'section_id');
                $section_settings = Sectionsetting::where('test_id', $test->id)->pluck('instruction', 'section_id');
                $test_questions = Testquestion::with('category')
                                ->where('test_id', $test->id)
                                ->groupBy('category_id')
                                ->select(DB::raw('COUNT(id) as total'), 'category_id')
                                ->get();

                $total_questions = Testquestion::where('test_id', $test->id)->count();

                $i = 0; $categories = [];
                foreach($test_questions as $question) {
                    $categories[$i]['id'] = $question->category_id;
                    $categories[$i]['name'] = $question->category->name;
                    $categories[$i]['info'] = isset($section_settings[$question->category_id])?$section_settings[$question->category_id]:'';
                    $categories[$i]['order'] = isset($order_settings[$question->category_id])?$order_settings[$question->category_id]:0;
                    $i++;
                }

                $columns = array_column($categories, 'order');
                array_multisort($columns, SORT_ASC, $categories);

                if(empty($categories)) {
                    return response()->json([
                            'success' => false,
                            'error' => 'This test have not created completed. Please contact your administrator for assistance.',
                        ], 400);
                }

                $test_taker = [];
                $totalDuration = '';
                if($request->cid != 'YWRtaW4=') {
                    $test_taker = Testtaker::with(['answers'])
                            ->where('email', $candidate->email)
                            ->where('test_id', $test->id)
                            ->where('status', 1)
                            ->orderBy('id', 'desc')
                            ->first();

                    if(!empty($test_taker)) {
                        $totalDuration = Carbon::now()->diffInMinutes($test_taker->created_at);
                    }
                }

                return response()->json([
                    'success' => true,
                    'message' => 'Online Test',
                    'test' => $test,
                    'test_settings' => $test_settings,
                    'category' => $categories,
                    'total_questions' => $total_questions,
                    'test_taker' => $test_taker,
                    'total_duration' => $totalDuration
                ], 200);
            }
        }
       
        return response()->json([
            'success' => false,
            'error' => 'This test has been deactivated. Please contact your administrator for assistance.',
        ], 400);

    }


    public function registation(Request $request, $test_id) {

        if($test_id) {

            request()->validate([
                'email' => 'required|email|max:255',
                'name' => 'required|max:255'
            ]);

            $test = Test::where('id', $test_id)->with('test_questions', 'test_questions.question', 'test_questions.question.options', 'test_questions.category')->first();

            $total_marks = 0;
            if(!empty($test->test_questions)) {
                foreach($test->test_questions as $item) {
                    $total_marks = $total_marks + $item->question->marks;
                }
            }

            $candidate = Assigncandidate::where('test_id', $test_id)
                        ->where('email', $request->email)
                        ->where('status', 0)
                        ->orderBy('id', 'desc')
                        ->first();

            if(!empty($candidate) && !empty($test)) {
                $testTaker = Testtaker::where('email', $request->email)->where('test_id', $test_id)->where('status', 1)->orderBy('id', 'desc')->first();
                if(empty($testTaker)) {

                    $taker = new Testtaker;
                    $taker->key = uniqid();
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
                    $taker->total_marks = $total_marks;
                    $taker->user_id = $candidate->user_id;
                    $taker->status = 1;
                    $taker->save();
                    
                    $answerLoad = [];
                    $i = 0;
                    foreach($test->test_questions as $item) {
                        $answerLoad[$i]['taker_id'] = $taker->id;
                        $answerLoad[$i]['question_id'] = $item->question->id;
                        $answerLoad[$i]['question'] = $item->question->title;
                        $answerLoad[$i]['category'] = $item->category->name;
                        $answerLoad[$i]['category_id'] = $item->question->category_id;
                        $answerLoad[$i]['option_one'] = $item->question->options[0]->option;
                        $answerLoad[$i]['option_two'] = $item->question->options[1]->option;
                        $answerLoad[$i]['option_three'] = $item->question->options[2]->option;
                        $answerLoad[$i]['option_four'] = $item->question->options[3]->option;
                        $answerLoad[$i]['correct'] = $item->question->correct;
                        $answerLoad[$i]['marks'] = $item->question->marks;
                        $answerLoad[$i]['test_id'] = $test_id;
                        $answerLoad[$i]['created_at'] = Date('Y-m-d H:i:s');
                        $answerLoad[$i]['updated_at'] = Date('Y-m-d H:i:s');
                        $i++;
                    }

                    Testtakeranswer::insert($answerLoad);

                    return response()->json([
                        'success' => true,
                        'message' => 'Registation successfully.',
                        'taker' => $taker
                    ], 200);

                }
                else {
                    return response()->json([
                        'success' => true,
                        'message' => 'Already Registation.',
                        'taker' => $testTaker
                    ], 200);
                }
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
        if(isset($request->selected) && isset($request->question_id) && $request->taker_id) {
            
            $answered = $request->selected + 1;
            $create_update = Testtakeranswer::updateOrCreate([
                                'taker_id' => $request->taker_id,
                                'question_id' => $request->question_id,
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
            Assigncandidate::where('test_id', $request->id)
                ->where('email', $request->taker_email)
                ->where('status', 0)
                ->update(['status' => 1]);
        }
    }

    public function report($taker_id) {
        if($taker_id) {
            $reportController = (new ReportController);
            $arrayData['success'] = true;
            $arrayData['success'] = true;
            $arrayData = $reportController->report_data($taker_id);

            return response()->json($arrayData, 200);
        }
    }

}
