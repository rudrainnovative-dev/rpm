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
use Auth;
class QuestionController extends Controller
{
    function __construct() {
         // $this->middleware('permission:question-list|question-create|question-edit|question-delete', ['only' => ['index','show']]);
         // $this->middleware('permission:question-create', ['only' => ['create','store']]);
         // $this->middleware('permission:question-edit', ['only' => ['edit','update']]);
         // $this->middleware('permission:question-delete', ['only' => ['destroy']]);
    }

    public function index(Request $request) {

        $search = "";
        if($request->has('search')) {
            $search = $request->search;
        }

        if($request->category_id) {
            $questions = Questionbank::where('category_id', $request->category_id)
                ->where('user_id', Auth::id())
                ->with(['category', 'options'])
                ->orderBy('id','desc')
                ->get();
            
            return response()->json([
                'success' => true,
                'message' => 'All Question.',
                'questions' => $questions
            ], 200);
        }
        else {
            $questions = Questionbank::with(['category', 'options'])
                        ->where('user_id', Auth::id())
                        ->where(function($query) use($search) {
                            if($search) {
                                $query->where('title', 'like', '%'.$search.'%');
                            }
                        })
                        ->orderBy('id','desc')
                        ->paginate(15);
            
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
        
        if(!Questionbank::where('title', $request->title)->exists()) {
            $question = new Questionbank;
            $question->category_id = $request->category_id;
            $question->title = $request->title;
            $question->marks = $request->marks;
            $question->correct = $request->correct;
            $question->answers_justification = $request->answers_justification;
            $question->user_id = Auth::id();
            $question->save();

            if($question->id) {
                foreach($request->options as $key => $value) {
                    if($value) {
                        $options = new Questionoption;
                        $options->question_id = $question->id;
                        $options->option = $value;
                        $options->user_id = Auth::id();
                        $options->save();
                    }
                } 
            }

            return response()->json([
                'success' => true,
                'message' => 'Question created successfully.'
            ], 200);
        }

        return response()->json([
                'success' => false,
                'error' => 'Question already exists.'
            ], 400);
    }

    public function show(Questionbank $question) {
        
        $options = Questionoption::where('question_id', $question->id)
                    ->where('user_id', Auth::id())
                    ->get();

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
        Questionbank::where('id', $question->id)->where('user_id', Auth::id())->delete();
        Questionoption::where('question_id', $question->id)->where('user_id', Auth::id())->delete();

        Testquestion::where('question_id', $question->id)->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Question deleted successfully.'
        ], 200);
    }

    public function getTestQuestion($test_id) {
        if($test_id) {
            $question_ids = Testquestion::where('test_id', $test_id)->where('user_id', Auth::id())->pluck('question_id');
            $questions = Questionbank::whereIn('id', $question_ids)->with(['category', 'options'])->get();
            return response()->json([
                'success' => true,
                'message' => 'All Question.',
                'questions' => $questions
            ], 200);
        }
    }

    public function uploadQuestions(Request $request) {
        if(!$request->file('excel_file')) {
            return response()->json([
                'success' => false,
                'message' => 'Required excel file.',
            ], 400);
        }

        $path = $request->file('excel_file');
        $extension = $request->file('excel_file')->getClientOriginalExtension();

        if($extension != 'xlsx' && $extension != '.xls') {
            return response()->json([
                'success' => false,
                'message' => 'Accept only excel file.',
            ], 400);
        }

        $excelData = Excel::toArray(new QuestionImport, $path);
        if(!empty($excelData[0])) {
            $header = array_keys($excelData[0][0]);
            $keys = ['category', 'question', 'option1', 'option2', 'option3', 'option4', 'correct', 'marks'];
            $matchHeader = array_intersect($header, $keys);
            if(count($matchHeader) == 8) {
                foreach($excelData[0] as $data) {
                    $category = Category::where('name', $data['category'])->select('id')->first();
                    if(!empty($category)) {
                        $question = Questionbank::firstOrCreate(
                            [
                                'category_id' => $category->id,
                                'title' => $data['question']
                            ],
                            [
                                'correct' => $data['correct'],
                                'marks' => $data['marks'],
                                'answers_justification' => isset($data['answer_of_justification'])?$data['answer_of_justification']:'',
                                'user_id' => Auth::id(),
                                'created_at' => Date('Y-m-d H:i:s'),
                                'updated_at' => Date('Y-m-d H:i:s')
                            ]
                        );

                        if($question->id && $question->wasRecentlyCreated) {
                            $options = [];
                            $k = 0;
                            for($i = 1; $i <= 4; $i++) {
                                $options[$k]['question_id'] = $question->id;
                                $options[$k]['option'] = $data['option'.$i];
                                $options[$k]['user_id'] = Auth::id();
                                $options[$k]['created_at'] = Date('Y-m-d H:i:s');
                                $options[$k]['updated_at'] = Date('Y-m-d H:i:s');
                                $k++;
                            }

                            Questionoption::insert($options);
                        }
                    }
                }

                return response()
                        ->json([
                            'success' => true,
                            'message' => 'Question Uploaded Successfully.'
                        ], 200);
            }
            else {
                return response()->json([
                    'success' => false,
                    'error' => 'Uploaded excel have not valid header columns.',
                ], 400);
            }
        }
        else {
            return response()
                ->json([
                    'success' => false,
                    'error' => 'Uploaded excel empty row value.',
                ], 400);
        }
    }
    

}
