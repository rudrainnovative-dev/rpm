<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Test;
use App\Models\Assign;
use App\Models\Assigncandidate;
use App\Models\Testtaker;
use Auth;
use Log;

class AssignController extends Controller
{
    public function index(Request $request) {

        $search = "";
        $filter = "";

        if($request->has('search')) {
            $search = $request->search;
        }

        if($request->has('filter')) {
            $filter = $request->filter;
        }
        $this->updateTestExpire();
        $assigned = Assigncandidate::with(['test', 'test_taker'])
                    ->where(function($query) use($search) {
                        if($search) {
                            $query->where('email', 'like', '%'.$search.'%');
                        }
                    })
                    ->where(function($query) use($filter) {
                        if($filter != '') {
                            $query->where('status', $filter);
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
            $requestData = $request->all();

            $assign = new Assign;
            $assign->user_id = Auth::id();
            $assign->created_at = Date('Y-m-d H:i:s');
            $assign->updated_at = Date('Y-m-d H:i:s');
            $assign->save();

            $start = Date('Y-m-d H:i:s', strtotime('+0 day'));
            $end = Date('Y-m-d H:i:s', strtotime('+1 day'));

            $resume = isset($request->settings['resume'])?$request->settings['resume']:0;

            if($request->default_check == true) {
                $start = Date('Y-m-d H:i:s', strtotime($request->settings['start']));
                $end = Date('Y-m-d H:i:s', strtotime($request->settings['end']));
            }

            foreach($request->lists as $list) {
                $checkCandidate = Assigncandidate::where('email', $list['email'])->where('test_id', $list['test_id'])->whereNotIn('status', ['1'])->first();

                if(empty($checkCandidate)) {
                    $assign_candidate = new Assigncandidate;
                    $assign_candidate->assign_id = $assign->id;

                    $name = explode("@", $list['email']);

                    $assign_candidate->name = $name[0];
                    $assign_candidate->email = $list['email'];
                    $assign_candidate->test_id = $list['test_id'];

                    if(!$request->default_check && $list['start']) {
                       $start = Date('Y-m-d H:i:s', strtotime($list['start']));
                    }

                    $assign_candidate->start = $start;

                    if(!$request->default_check && $list['end']) {
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
                    return response()->json([
                        'success' => true,
                        'message' => 'Test assigned to candidates successfully.',
                        'id' => $assign->id,
                        'actions' => $request->actions
                    ], 200);
                }else{
                    return response()->json([
                        'success' => false,
                        'message' => 'This test is already assigned to this user.',
                        'actions' => "not_saved"
                    ], 400);

                }
            }


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

    public function update(Request $request) {

        if(!empty($request->lists) && $request->message) {
            $data['subject'] = 'Online Test';
            $data['lists'] = $request->lists;
            $data['message'] = $request->message;

            // dd($data);
            $job = (new \App\Jobs\SendEmail($data))
                ->delay(now()->addSeconds(2));

            $this->dispatch($job);

            return response()->json([
                'success' => true,
                'message' => 'Mail sent.',
            ], 200);

        }else if(isset($request->for) && $request->for == "update_assigned_candidate") {

            $mess = Assigncandidate::where('id', $request->id)
            ->update($request->candidate);
            if($mess)
                return response()->json([
                    'success' => true,
                    'message' => 'Details updated to assigned candidates successfully.',
                ], 200);
            else
                return response()->json([
                    'success' => false,
                    'message' => 'Details not updated of assigned candidates',
                ], 400);
        }
    }

    public function destroy($id) {
        Testtaker::whereRaw("email in (select email from assign_candidates where id = $id)")->delete();
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

    public function updateTestExpire() {
        // status 0 is for pending
        // status -1 is for in-progress
        // status 1 is for completed
        // status 2 is for expired
        return Assigncandidate::where(['user_id'=>Auth::id(),'status'=>-1])->orWhere(['status'=>0])->whereDate('end','<',date('Y-m-d H:i:s'))->update(['status'=>2]);
    }
}
