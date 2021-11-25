<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Test;
use App\Model\Assign;

class AssignController extends Controller
{
    function __construct() {
         // $this->middleware('permission:question-list|question-create|question-edit|question-delete', ['only' => ['index','show']]);
         // $this->middleware('permission:question-create', ['only' => ['create','store']]);
         // $this->middleware('permission:question-edit', ['only' => ['edit','update']]);
         // $this->middleware('permission:question-delete', ['only' => ['destroy']]);
    }

    public function index(Request $request) {

    }

    public function create() {
        //
    }

    public function store(Request $request) {
        
    }

    public function show(Assign $assign) {
       
    }

    public function edit(Assign $assign) {
        //
    }

    public function update(Request $request, Assign $assign) {
        
    }
    
    public function destroy(Assign $assign) { 
        
    }

}
