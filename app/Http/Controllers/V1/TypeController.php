<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Type;

class TypeController extends Controller
{
    function __construct() {
         // $this->middleware('permission:type-list|type-create|type-edit|type-delete', ['only' => ['index','show']]);
         // $this->middleware('permission:type-create', ['only' => ['create','store']]);
         // $this->middleware('permission:type-edit', ['only' => ['edit','update']]);
         // $this->middleware('permission:type-delete', ['only' => ['destroy']]);
    }

    public function index() {
        $types = Type::get();
        
        return response()->json([
            'success' => true,
            'message' => 'All Types.',
            'types' => $types
        ], 200);
    }

    public function create() {
        //
    }

    public function store(Request $request) {
        request()->validate([
            'type' => 'required'
        ]);
        
        Type::firstOrCreate($request->all());
        
        return response()->json([
            'success' => true,
            'message' => 'Type created successfully.'
        ], 200);
    }

    public function show(Type $type) {
        
        return response()->json([
            'success' => true,
            'message' => 'Type retrive successfully.',
            'type' => $type
        ], 200);
    }

    public function edit(Type $type) {
        //
    }

    public function update(Request $request, Type $type) {
         request()->validate([
            'type' => 'required'
        ]);
    
        $type->update($request->all());
    
        return response()->json([
            'success' => true,
            'message' => 'Type updated successfully.',
            'type' => $type
        ], 200);
    }
    
    public function destroy(Type $type) {
        $type->delete();
    
        return response()->json([
            'success' => true,
            'message' => 'Type deleted successfully.'
        ], 200);
    }
}
