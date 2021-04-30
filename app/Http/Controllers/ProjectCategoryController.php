<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

use App\Models\ProjectCategory;
use DB;

class ProjectCategoryController extends Controller
{
    /**
     * Handle an authentication attempt.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return Response
     */
    public function add_project_category(Request $request)
    {
       
        $validator = $request->validate([
            'category_name' => 'required',
        ]);
        
        $category = new ProjectCategory;
        $category->category_name = $request->input('category_name');
        $category->status = $request->input('status');
        
        if($category->save()) {
            return response()->json(['status'=>'1','message' => 'Successfully Project Category added.'], 200);
        } else {
            return response()->json(['status'=>'0','message' => 'Error occured in Project Category add.'], 422);
        }
    }

    public function project_categories() {
        $categories = ProjectCategory::all();
        return response()->json(['status'=>'1','message' => 'Projectt Category List', 'categories' => $categories], 200);
    }

    public function edit_project_category(Request $request) {
        $validator = $request->validate([
            'category_name' => 'required',
            'category_id' => 'required',
            'status' => 'required',
        ]);
        
        $category = ProjectCategory::find($request->input('category_id'));
        $category->category_name = $request->input('category_name');
        $category->status = $request->input('status');
        
        if($category->save()) {
            return response()->json(['status'=>'1','message' => 'Successfully Project Category updated.'], 200);
        } else {
            return response()->json(['status'=>'0','message' => 'Error occured in Project Category update.'], 422);
        }
    }
}