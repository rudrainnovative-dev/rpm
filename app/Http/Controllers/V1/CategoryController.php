<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{   
    function __construct() {
         // $this->middleware('permission:category-list|category-create|category-edit|category-delete', ['only' => ['index','show']]);
         // $this->middleware('permission:category-create', ['only' => ['create','store']]);
         // $this->middleware('permission:category-edit', ['only' => ['edit','update']]);
         // $this->middleware('permission:category-delete', ['only' => ['destroy']]);
    }

    public function index() {
        $categories = Category::all();
        
        return response()->json([
            'success' => true,
            'message' => 'All Categories.',
            'categories' => $categories
        ], 200);
    }

    public function create() {
        //
    }

    public function store(Request $request) {
        request()->validate([
            'name' => 'required|max:255'
        ]);
    
        Category::firstOrCreate($request->all());
        
        return response()->json([
            'success' => true,
            'message' => 'Category created successfully.'
        ], 200);
    }

    public function show(Category $category) {
        
        return response()->json([
            'success' => true,
            'message' => 'Category created successfully.',
            'category' => $category
        ], 200);
    }

    public function edit(Category $category) {
        //
    }

    public function update(Request $request, Category $category) {
         request()->validate([
            'name' => 'required'
        ]);
    
        $category->update($request->all());
    
        return response()->json([
            'success' => true,
            'message' => 'Category updated successfully.',
            'category' => $category
        ], 200);
    }
    
    public function destroy(Category $category) {
        $category->delete();
    
        return response()->json([
            'success' => true,
            'message' => 'Category deleted successfully.'
        ], 200);
    }
}
