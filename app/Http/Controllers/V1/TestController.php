<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Testpurpose;
use App\Models\Test;
use App\Models\Testsetting;
use App\Models\Sectionsetting;
use App\Models\Ordersetting;
use App\Models\Testquestion;
use App\Models\Registationfield;
use Auth;

class TestController extends Controller
{
    function __construct() {
         // $this->middleware('permission:question-list|question-create|question-edit|question-delete', ['only' => ['index','show']]);
         // $this->middleware('permission:question-create', ['only' => ['create','store']]);
         // $this->middleware('permission:question-edit', ['only' => ['edit','update']]);
         // $this->middleware('permission:question-delete', ['only' => ['destroy']]);
    }

    public function index(Request $request) {
        if($request->test) {
            $tests = Test::with('purpose')
                        ->where('user_id', Auth::id())
                        ->select('id', 'name')
                        ->orderBy('id', 'desc')
                        ->get();

            return response()->json([
                'success' => true,
                'message' => 'All Question.',
                'tests' => $tests
            ], 200);
        }
        else {
            $tests = Test::with(['purpose','test_questions'])
                        ->withCount(['taker'])
                        ->where('user_id', Auth::id())
                        ->orderBy('id', 'desc')
                        ->paginate(10);

            return response()->json([
                'success' => true,
                'message' => 'All Question.',
                'tests' => $tests,
            ], 200);
        }
    }

    public function create() {
        //
    }

    public function store(Request $request) {
        request()->validate([
            'name' => 'required',
            'purpose_id' => 'required',
            'assessment_type' => 'required'
        ]);
        
        $test = new Test;
        $test->name = $request->name;
        $test->purpose_id = $request->purpose_id;
        $test->assessment_type = $request->assessment_type;
        $test->public_id = uniqid();
        $test->user_id = Auth::id();
        $test->save();

        foreach($this->default_fields() as $field) {
            $registation = new Registationfield;
            $registation->test_id = $test->id;
            $registation->user_id = Auth::id();
            $registation->field = $field['field'];
            $registation->required = $field['required'];
            $registation->type = $field['type'];
            $registation->allow = $field['allow'];
            $registation->created_at = Date('Y-m-d H:i:s');
            $registation->updated_at = Date('Y-m-d H:i:s');
            $registation->save();
        }

        return response()->json([
            'success' => true,
            'test_id'=> $test->id,
            'message' => 'Test created successfully.'
        ], 200);
    }

    public function show(Test $test) {
        
        $test_settings = Testsetting::where('test_id', $test->id)
                            ->where('user_id', Auth::id())
                            ->pluck('criteria_id');

        $section_settings = Sectionsetting::where('test_id', $test->id)
                            ->where('user_id', Auth::id())
                            ->pluck('instruction', 'section_id');

        $order_settings = Ordersetting::where('test_id', $test->id)
                            ->where('user_id', Auth::id())
                            ->pluck('order', 'section_id');
        
        $test_questions = Testquestion::with(['question', 'category'])
                            ->where('user_id', Auth::id())
                            ->where('test_id', $test->id)
                            ->orderBy('id', 'asc')
                            ->get();

        $selected_questions = [];
        $i=0;
        foreach($test_questions as $list) {
            $selected_questions[$i]['question_id'] = $list->question_id;
            $selected_questions[$i]['category_id'] = $list->category_id;
            $selected_questions[$i]['category'] = $list->category->name;
            $selected_questions[$i]['marks'] = $list->question->marks;
            $i++;
        }

        return response()->json([
            'success' => true,
            'message' => 'Test retrive successfully.',
            'test' => $test,
            'test_settings' => $test_settings,
            'section_settings' => $section_settings,
            'order_settings' => $order_settings,
            'selected_questions' => $selected_questions
        ], 200);
    }

    public function edit(Test $test) {
        //
    }

    public function update(Request $request, Test $test) {
        
        request()->validate([
            'name' => 'required',
            'purpose_id' => 'required',
            'assessment_type' => 'required',
            'duration' => 'required'
        ]);
    
        $fromDate = Date('Y-m-d H:i:s', strtotime($request->assessment_time_from));
        $toDate = Date('Y-m-d H:i:s', strtotime($request->assessment_time_to));

        $test->update([
                        'name' => $request->name,
                        'purpose_id' => $request->purpose_id,
                        'assessment_type' => $request->assessment_type,
                        'duration' => $request->duration,
                        'assessment_time_from' => $fromDate,
                        'assessment_time_to' => $toDate,
                        'timezone' => date_default_timezone_get()
                    ]);
        

        // test question list
        $allQuestions = [];
        $i = 0;
        foreach($request->selected_question as $data) {
            $allQuestions[$i]['test_id'] = $test->id;
            $allQuestions[$i]['question_id'] = $data['question_id'];
            $allQuestions[$i]['category_id'] = $data['category_id'];
            $allQuestions[$i]['user_id'] = Auth::id();
            $allQuestions[$i]['created_at'] = Date('Y-m-d H:i:s');
            $allQuestions[$i]['updated_at'] = Date('Y-m-d H:i:s');
            $i++;
        }

        Testquestion::where('test_id', $test->id)->where('user_id', Auth::id())->delete();
        if(!empty($allQuestions)) {
            Testquestion::insert($allQuestions);
        }

        // test setting insert
        $test_settings = [];
        $i = 0;
        foreach($request->test_settings as $v) {
            if($v) {
                $test_settings[$i]['test_id'] = $test->id;
                $test_settings[$i]['criteria_id'] = $v;
                $test_settings[$i]['user_id'] = Auth::id();
                $test_settings[$i]['created_at'] = Date('Y-m-d H:i:s');
                $test_settings[$i]['updated_at'] = Date('Y-m-d H:i:s');
                $i++;
            }
        }

        Testsetting::where('test_id', $test->id)->where('user_id', Auth::id())->delete();
        if(!empty($test_settings)) {
            Testsetting::insert($test_settings);
        }

        // section settings insert
        $section_settings = [];
        $i = 0;
        foreach($request->section_settings as $k => $v) {
            if($v) {
                $section_settings[$i]['test_id'] = $test->id;
                $section_settings[$i]['section_id'] = $k;
                $section_settings[$i]['instruction'] = $v;
                $section_settings[$i]['user_id'] = Auth::id();
                $section_settings[$i]['created_at'] = Date('Y-m-d H:i:s');
                $section_settings[$i]['updated_at'] = Date('Y-m-d H:i:s');
                $i++;
            }
        }

        Sectionsetting::where('test_id', $test->id)->where('user_id', Auth::id())->delete();
        if(!empty($section_settings)) {
            Sectionsetting::insert($section_settings);
        }

        // order settings
        $order_settings = [];
        $i = 0;
        foreach($request->order_settings as $k=>$v) {
            if($v) {
                $order_settings[$i]['test_id'] = $test->id;
                $order_settings[$i]['section_id'] = $k;
                $order_settings[$i]['order'] = $v;
                $order_settings[$i]['user_id'] = Auth::id();
                $order_settings[$i]['created_at'] = Date('Y-m-d H:i:s');
                $order_settings[$i]['updated_at'] = Date('Y-m-d H:i:s');
                $i++;
            }
        }

        Ordersetting::where('test_id', $test->id)->where('user_id', Auth::id())->delete();
        if(!empty($order_settings)) {
            Ordersetting::insert($order_settings);
        }


        return response()->json([
            'success' => true,
            'message' => 'Test updated successfully.',
            'test' => $test
        ], 200);
    }
    
    public function destroy(Test $test) {
        $test->delete();
        
        Testquestion::where('test_id', $test->id)->where('user_id', Auth::id())->delete();
        Testsetting::where('test_id', $test->id)->where('user_id', Auth::id())->delete();
        Sectionsetting::where('test_id', $test->id)->where('user_id', Auth::id())->delete();
        Ordersetting::where('test_id', $test->id)->where('user_id', Auth::id())->delete();
        Registationfield::where('test_id', $test->id)->where('user_id', Auth::id())->delete();
        Assigncandidate::where('test_id', $test->id)->where('user_id', Auth::id())->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Test deleted successfully.'
        ], 200);
    }

    public function duplicate(Request $request) {

        if($request->id && $request->name) {
            $test = Test::find($request->id);
            if(!empty($test)) {
                $copy_test = new Test;
                $copy_test->public_id = uniqid();
                $copy_test->name = $request->name;
                $copy_test->purpose_id = $test->purpose_id;
                $copy_test->assessment_time_from = $test->assessment_time_from;
                $copy_test->assessment_time_to = $test->assessment_time_to;
                $copy_test->assessment_type = $test->assessment_type;
                $copy_test->duration = $test->duration;
                $copy_test->timezone = $test->timezone;
                $copy_test->user_id = Auth::id();
                $copy_test->created_at = Date('Y-m-d H:i:s');
                $copy_test->updated_at = Date('Y-m-d H:i:s');
                $copy_test->save();
            
               $test_questions = Testquestion::where('test_id', $request->id)->where('user_id', Auth::id())->get();
               $copy_questions = [];
               $i = 0;
               foreach($test_questions as $question) {
                    $copy_questions[$i]['test_id'] = $copy_test->id;
                    $copy_questions[$i]['question_id'] = $question->question_id;
                    $copy_questions[$i]['category_id'] = $question->category_id;
                    $copy_questions[$i]['user_id'] = Auth::id();
                    $copy_questions[$i]['created_at'] = Date('Y-m-d H:i:s');
                    $copy_questions[$i]['updated_at'] = Date('Y-m-d H:i:s');
                    $i++;
               }

               if(!empty($copy_questions)) {
                    Testquestion::insert($copy_questions);
               }

               $test_settings = Testsetting::where('test_id', $request->id)->where('user_id', Auth::id())->get();
               $copy_test_settings = [];
               $i = 0;
               foreach($test_settings as $test) {
                    $copy_test_settings[$i]['test_id'] = $copy_test->id;
                    $copy_test_settings[$i]['criteria_id'] = $test->criteria_id;
                    $copy_test_settings[$i]['user_id'] = Auth::id();
                    $copy_test_settings[$i]['created_at'] = Date('Y-m-d H:i:s');
                    $copy_test_settings[$i]['updated_at'] = Date('Y-m-d H:i:s');
                    $i++;
                }

                if(!empty($copy_test_settings)) {
                    Testsetting::insert($copy_test_settings);
                }

                $section_settings = Sectionsetting::where('test_id', $request->id)->where('user_id', Auth::id())->get();
                $copy_section_settings = [];
                $i = 0;
                foreach($section_settings as $sec) {
                    $copy_section_settings[$i]['test_id'] = $copy_test->id;
                    $copy_section_settings[$i]['section_id'] = $sec->section_id;
                    $copy_section_settings[$i]['instruction'] = $sec->instruction;
                    $copy_section_settings[$i]['user_id'] = Auth::id();
                    $copy_section_settings[$i]['created_at'] = Date('Y-m-d H:i:s');
                    $copy_section_settings[$i]['updated_at'] = Date('Y-m-d H:i:s');
                    $i++;
                }

                if(!empty($copy_section_settings)) {
                    Sectionsetting::insert($copy_section_settings);
                }

                $order_settings = Ordersetting::where('test_id', $request->id)->where('user_id', Auth::id())->get();
                $copy_order_settings = [];
                $i = 0;
                foreach($order_settings as $ord) {
                    $copy_order_settings[$i]['test_id'] = $copy_test->id;
                    $copy_order_settings[$i]['section_id'] = $ord->section_id;
                    $copy_order_settings[$i]['order'] = $ord->order;
                    $copy_order_settings[$i]['user_id'] = Auth::id();
                    $copy_order_settings[$i]['created_at'] = Date('Y-m-d H:i:s');
                    $copy_order_settings[$i]['updated_at'] = Date('Y-m-d H:i:s');
                    $i++;
                }

                if(!empty($copy_order_settings)) {
                    Ordersetting::insert($copy_order_settings);
                }

                $registation_fields = Registationfield::where('test_id', $request->id)->where('user_id', Auth::id())->get();
                $copy_registation_fields = [];
                $i = 0;
                foreach($registation_fields as $regist) {
                    $copy_registation_fields[$i]['test_id'] = $copy_test->id;
                    $copy_registation_fields[$i]['field'] = $regist->field;
                    $copy_registation_fields[$i]['type'] = $regist->type;
                    $copy_registation_fields[$i]['required'] = $regist->required;
                    $copy_registation_fields[$i]['allow'] = $regist->allow;
                    $copy_registation_fields[$i]['user_id'] = Auth::id();
                    $copy_registation_fields[$i]['created_at'] = Date('Y-m-d H:i:s');
                    $copy_registation_fields[$i]['updated_at'] = Date('Y-m-d H:i:s');
                    $i++;
                }

                if(!empty($copy_registation_fields)) {
                    Registationfield::insert($copy_registation_fields);
                }

                return response()->json([
                    'success' => true,
                    'message' => 'Created test copy successfully.',
                    'data' => $test
                ], 200);

            }
        }
    }

    public function questions($test_id) {
        $test = Test::where('id', $test_id)->where('user_id', Auth::id())->select('id', 'name')->first();
        $test_questions = Testquestion::where('test_id', $test_id)->where('user_id', Auth::id())->with(['question', 'category'])->paginate(10);
        
        return response()->json([
                'success' => true,
                'message' => 'Test questions list.',
                'test' => $test,
                'test_questions' => $test_questions
            ], 200);
    }

    public function default_fields() {
        return [
                    [ 'field' => 'Name', 'required' => 1, 'type' => 'text', 'allow' => 1 ],
                    [ 'field' => 'Email', 'required' => 1, 'type' => 'email', 'allow' => 1 ],
                    [ 'field' => 'Phone Number', 'required' => 0, 'type' => 'number', 'allow' => 0 ],
                    [ 'field' => 'Date of Birth', 'required' => 0, 'type' => 'date', 'allow' => 0 ],
                    [ 'field' => 'Gender', 'required' => 0, 'type' => 'dropdown', 'allow' => 0 ],
                    [ 'field' => 'Profile Picture', 'required' => 0, 'type' => 'file', 'allow' => 0 ],
                    [ 'field' => 'Identity card', 'required' => 0, 'type' => 'file', 'allow' => 0 ]
            ];
    }
}
