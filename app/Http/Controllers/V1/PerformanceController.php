<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Performancetype;
use App\Models\Performancecriteria;
use App\Models\Performancecriteriaoption;
use Auth;

class PerformanceController extends Controller
{
    function __construct() {
         // $this->middleware('permission:question-list|question-create|question-edit|question-delete', ['only' => ['index','show']]);
         // $this->middleware('permission:question-create', ['only' => ['create','store']]);
         // $this->middleware('permission:question-edit', ['only' => ['edit','update']]);
         // $this->middleware('permission:question-delete', ['only' => ['destroy']]);
    }

    public function index(Request $request) {
        
        $current_performance = Performancecriteria::with(['options'])->where('user_id', Auth::id())->orderBy('id', 'desc')->first();
        $performance_criterias = Performancecriteria::where('user_id', Auth::id())->orderBy('id', 'desc')->get();
        $performance_type = Performancetype::orderBy('id', 'asc')->get();

        return response()->json([
            'success' => true,
            'message' => 'All criterias.',
            'performance_criterias' => $performance_criterias,
            'current_performance' => $current_performance,
            'performance_type' => $performance_type
        ], 200);
        
    }

    public function create() {
        //
    }

    public function store(Request $request) {
        
        $select = Performancecriteria::orderBy('id', 'desc')->where('user_id', Auth::id())->first();
        if(!empty($select)) {
            Performancecriteria::where('id', $select->id)->update(['updated_at' => Date('Y-m-d H:i:s', strtotime('-60 seconds'))]);
        }

        $performanceCriteria = new Performancecriteria;
        $performanceCriteria->user_id = Auth::id();
        $performanceCriteria->save();

        $options = [];
        $i = 0;
        foreach($request->data as $id => $value) {
            if($value) {

                foreach($value as $val) {
                    if($val) {
                        $options[$i]['performance_criteria_id'] = $performanceCriteria->id;
                        $options[$i]['performance_type_id'] = $id;
                        $options[$i]['criteria'] = isset($val['criteria'])?$val['criteria']:'';
                        $options[$i]['formula'] = isset($val['formula'])?$val['formula']:'';
                        $options[$i]['score'] = isset($val['score'])?$val['score']:'';
                        $options[$i]['remarks'] = isset($request->remarks[$id])?$request->remarks[$id]:'';
                        $options[$i]['user_id'] = Auth::id();
                        $options[$i]['created_at'] = Date('Y-m-d H:i:s');
                        $options[$i]['updated_at'] = Date('Y-m-d H:i:s');
                        $i++;
                    }
                }
            }
        }

        if(!empty($options)) {
            Performancecriteriaoption::insert($options);
        }

        return response()->json([
            'success' => true,
            'message' => 'Performance criteria created successfully.',
        ], 200);
    }

    public function show(Performancecriteria $performance) {
    
        $current_performance = Performancecriteria::with(['options'])->where('user_id', Auth::id())->where('id', $performance->id)->first();
        $performance_type = Performancetype::orderBy('id', 'asc')->get();
        return response()->json([
            'success' => true,
            'message' => 'Retrive successfully.',
            'performance' => $performance,
            'performance_type' => $performance_type,
            'current_performance' => $current_performance
        ], 200);
    }

    public function edit(Test $test) {
        //
    }

    public function update(Request $request, Performancecriteria $performance) {
        
        request()->validate([
            'from' => 'required',
            'to' => 'required',
        ]);
        
        $performance->update([
            'from' => $request->from,
            'to' => $request->to,
        ]);

        $options = [];
        $i = 0;
        foreach($request->criteria as $type_id => $value) {
            if($value) {
                $options[$i]['performance_criteria_id'] = $performance->id;
                $options[$i]['performance_type_id'] = $type_id;
                $options[$i]['criteria'] = $value;
                $options[$i]['formula'] = isset($request->formula[$type_id])?$request->formula[$type_id]:'';
                $options[$i]['score'] = isset($request->score[$type_id])?$request->score[$type_id]:'';
                $options[$i]['remarks'] = isset($request->remarks[$type_id])?$request->remarks[$type_id]:'';
                $options[$i]['user_id'] = Auth::id();
                $options[$i]['created_at'] = Date('Y-m-d H:i:s');
                $options[$i]['updated_at'] = Date('Y-m-d H:i:s');
                $i++;
            }
        }

        if(!empty($options)) {
            Performancecriteriaoption::where('performance_criteria_id', $performance->id)->where('user_id', Auth::id())->delete();
            Performancecriteriaoption::insert($options);
        }

        return response()->json([
            'success' => true,
            'message' => 'Data updated successfully.',
        ], 200);
    }
    
    public function destroy(Performancecriteria $performance) {
        
        $performance->delete();
        Performancecriteriaoption::where('performance_criteria_id', $performance->id)->where('user_id', Auth::id())->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Performance criteria deleted successfully.'
        ], 200);
    }
}
