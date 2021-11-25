<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Questionbank;
use App\Models\Questionoption;
use App\Models\Testquestion;
use App\Models\Category;
use App\Imports\QuestionImport;
use Excel;
use File;

class QuestionController extends Controller
{
    function __construct() {
         // $this->middleware('permission:question-list|question-create|question-edit|question-delete', ['only' => ['index','show']]);
         // $this->middleware('permission:question-create', ['only' => ['create','store']]);
         // $this->middleware('permission:question-edit', ['only' => ['edit','update']]);
         // $this->middleware('permission:question-delete', ['only' => ['destroy']]);
    }

    public function index(Request $request) {

        if($request->category_id) {
            $questions = Questionbank::where('category_id', $request->category_id)->with(['category', 'options'])->orderBy('id','desc')->get();
            
            return response()->json([
                'success' => true,
                'message' => 'All Question.',
                'questions' => $questions
            ], 200);
        }
        else {
            $questions = Questionbank::with(['category', 'options'])->orderBy('id','desc')->paginate(5);
            
            return response()->json([
                'success' => true,
                'message' => 'Question list.',
                'questions' => $questions
            ], 200);
        }
    }

    public function create() {
        //
    }

    public function store(Request $request) {
        request()->validate([
            'category_id' => 'required',
            'title' => 'required',
            'marks' => 'required'
        ]);
        
        $question = new Questionbank;
        $question->category_id = $request->category_id;
        $question->title = $request->title;
        $question->marks = $request->marks;
        $question->correct = $request->correct;
        $question->answers_justification = $request->answers_justification;
        $question->save();

        if($question->id) {
            foreach($request->options as $key => $value) {
                if($value) {
                    $options = new Questionoption;
                    $options->question_id = $question->id;
                    $options->option = $value;
                    $options->save();
                }
            } 
        }

        return response()->json([
            'success' => true,
            'message' => 'Question created successfully.'
        ], 200);
    }

    public function show(Questionbank $question) {
        
        $options = Questionoption::where('question_id', $question->id)->get();

        return response()->json([
            'success' => true,
            'message' => 'Question retrive successfully.',
            'question' => $question,
            'options' => $options
        ], 200);
    }

    public function edit(Questionbank $question) {
        //
    }

    public function update(Request $request, Questionbank $question) {
         request()->validate([
            'category_id' => 'required',
            'title' => 'required',
            'marks' => 'required'
        ]);
    
        $question->update([
                            'category_id' => $request->category_id,
                            'title' => $request->title,
                            'marks' => $request->marks,
                            'correct' => $request->correct,
                            'answers_justification' => $request->answers_justification
                        ]);
        
        if($question->id) {
            foreach($request->option_ids as $key => $value) {
                if($value) {    
                    Questionoption::where('id', $value)
                    ->where('question_id', $question->id)
                    ->update([
                        'option' => $request->options[$key]
                    ]);
                }
            } 
        }

        return response()->json([
            'success' => true,
            'message' => 'Question updated successfully.',
            'question' => $question
        ], 200);
    }
    
    public function destroy(Questionbank $question) {
        $question->delete();
        Questionoption::where('question_id', $question->id)->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Question deleted successfully.'
        ], 200);
    }

    public function getTestQuestion($test_id) {
        if($test_id) {
            $question_ids = Testquestion::where('test_id', $test_id)->pluck('question_id');
            $questions = Questionbank::whereIn('id', $question_ids)->with(['category', 'options'])->get();
            return response()->json([
                'success' => true,
                'message' => 'All Question.',
                'questions' => $questions
            ], 200);
        }
    }

    public function uploadQuestions(Request $request) {
        // $request->validate([
        //     'excel_file.*' => 'required|file|mimes:xls,xlsx'
        // ]);

        // $path = $request->file('excel_file');
        // $excelData = Excel::toArray(new QuestionImport, $path);

        // if(!empty($excelData[0])) {
        //     foreach($excelData[0] as $data) {
        //         if(isset($data['category']) && $data['category'] != '' && isset($data['question']) && $data['question'] != '') {
        //             $category = Category::where('name', $data['category'])->select('id')->first();
        //             if(!empty($category)) {

        //                 $question = new Questionbank;
        //                 $question->category_id = $category->id;
        //                 $question->title = $data['question'];
        //                 $question->answers_justification = isset($data['justification'])?$data['justification']:'';
        //                 $question->save();

        //                 if($question->id) {
        //                     $k = 0, $options = [];
        //                     for($i = 1; $i <= 4; $i++) {
        //                         if($data['option'.$i]) {
        //                             $options[$k]['question_id'] = $question->id;
        //                             $options[$k]['option'] = $data['option'.$i];

        //                             $options[$k]['correct'] = 0;
        //                             if(isset($data['correct'.$i]) && strtolower($data['correct'.$i]) == 'yes') {
        //                                 $options[$k]['correct'] = 1;
        //                             }

        //                             $options[$k]['marks'] = 0;
        //                             if(isset($data['marks'.$i]) && is_int($data['marks'.$i])) {
        //                                 $options[$k]['marks'] = $data['marks'.$i];
        //                             }
                                    
        //                             $options[$k]['created_at'] = Date('Y-m-d H:i:s');
        //                             $options[$k]['updated_at'] = Date('Y-m-d H:i:s');
        //                         }
        //                     }

        //                     Questionoption::insert($options);
        //                 }
        //             }
        //         }
        //     }
        //}

        // return response()->json([
        //         'success' => true,
        //         'message' => 'get',
        //         'excelData' => $f
        //     ], 200);
    }
}
