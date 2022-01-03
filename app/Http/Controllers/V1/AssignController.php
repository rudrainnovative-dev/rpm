<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Test;
use App\Models\Assign;
use App\Models\Assigncandidate;
use Auth;

class AssignController extends Controller
{

    public function index(Request $request) {
        
        $search = "";
        if($request->has('search')) {
            $search = $request->search;
        }

        $assigned = Assigncandidate::with(['test'])
                    ->where(function($query) use($search) {
                        if($search) {
                            $query->where('email', 'like', '%'.$search.'%');
                        }
                    })
                    ->orderBy('id', 'desc')
                    ->where('user_id', Auth::id())
                    ->paginate(10);
        
        return response()->json([
                'success' => true,
                'message' => 'Assigned lists.',
                'assigned' => $assigned
            ], 200);
    }

    public function create() {
        //
    }

    public function store(Request $request) {
        
        if(isset($request->lists)) {
            
            $assign = new Assign;
            $assign->user_id = Auth::id();
            $assign->created_at = Date('Y-m-d H:i:s');
            $assign->updated_at = Date('Y-m-d H:i:s');
            $assign->save();

            $start = Date('Y-m-d H:i:s', strtotime('+0 day'));
            $end = Date('Y-m-d H:i:s', strtotime('+1 day'));

            $resume = isset($request->settings['resume'])?$request->settings['resume']:0;

            if(isset($request->default_check)) {
                $start = Date('Y-m-d H:i:s', strtotime($request->settings['start']));
                $end = Date('Y-m-d H:i:s', strtotime($request->settings['end']));    
            }

            foreach($request->lists as $list) {
                $checkCandidate = Assigncandidate::where('email', $list['email'])->where('test_id', $list['test_id'])->where('status', '0')->first();
                if(empty($checkCandidate)) {
                    $assign_candidate = new Assigncandidate;
                    $assign_candidate->assign_id = $assign->id;

                    $name = explode("@", $list['email']);

                    $assign_candidate->name = $name[0];
                    $assign_candidate->email = $list['email'];
                    $assign_candidate->test_id = $list['test_id'];

                    if(!isset($request->default_check) && $list['start']) {
                       $start = Date('Y-m-d H:i:s', strtotime($list['start']));
                    }

                    $assign_candidate->start = $start;

                    if(!isset($request->default_check) && $list['end']) {
                       $end = Date('Y-m-d H:i:s', strtotime($list['end']));
                    }

                    $assign_candidate->end = $end;

                    if(!$resume) {
                        $resume = $list['resume'];
                    }

                    $assign_candidate->resume = $resume;
                    $assign_candidate->assessment_type = $list['type'];
                    $assign_candidate->share = 0;
                    $assign_candidate->user_id = Auth::id();
                    $assign_candidate->save(); 
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Test assigned to candidates successfully.',
                'id' => $assign->id,
                'actions' => $request->actions
            ], 200);
        }

    }

    public function show($id) {

        $assign = Assign::where('id', $id)->where('user_id', Auth::id())->with(['candidates', 'candidates.test'])->first();

        return response()->json([
            'success' => true,
            'message' => 'Candidates list.',
            'assign' => $assign
        ], 200);
    }

    public function edit(Assign $assign) {
        //
    }

    public function update(Request $request, Assign $assign) {
        
        if(!empty($request->lists) && $request->message) {
            
            $data['subject'] = 'Online Test';
            $data['lists'] = $request->lists;
            $data['message'] = $request->message;

            $job = (new \App\Jobs\SendEmail($data))
                ->delay(now()->addSeconds(2)); 

            $this->dispatch($job);
            
            return response()->json([
                'success' => true,
                'message' => 'Mail sent.',
            ], 200);
            
        }
    }
    
    public function destroy($id) { 
        Assigncandidate::where('id', $id)->where('user_id', Auth::id())->delete();
        return response()->json([
            'success' => true,
            'message' => 'Candidates deleted successfully.',
        ], 200);
    }

    public function showCandidate($id) {
        $assign = Assigncandidate::where('id', $id)->where('user_id', Auth::id())->with(['test'])->first();
        return response()->json([
            'success' => true,
            'message' => 'Candidates list.',
            'assign' => $assign
        ], 200);
    }
}
