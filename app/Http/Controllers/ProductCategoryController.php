<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

use App\Models\ProductCategory;
use DB;

class ProductCategoryController extends Controller
{
    /**
     * Handle an authentication attempt.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return Response
     */
    public function add_product_category(Request $request)
    {
        $validator = $request->validate([
            'category_name' => 'required',
        ]);
        
        $category = new ProductCategory;
        $category->category_name = $request->input('category_name');
        $category->status = $request->input('status');
        
        if($category->save()) {
            return response()->json(['status'=>'1','message' => 'Successfully Product Category added.'], 200);
        } else {
            return response()->json(['status'=>'0','message' => 'Error occured in Product Category add.'], 422);
        }
    }

    public function product_categories() {
        $categories = ProductCategory::all();
        return response()->json(['status'=>'1','message' => 'Product Category List', 'categories' => $categories], 200);
    }

    public function edit_product_category(Request $request) {
        $validator = $request->validate([
            'category_name' => 'required',
            'category_id' => 'required',
            'status' => 'required',
        ]);
        
        $category = ProductCategory::find($request->input('category_id'));
        $category->category_name = $request->input('category_name');
        $category->status = $request->input('status');
        
        if($category->save()) {
            return response()->json(['status'=>'1','message' => 'Successfully Product Category updated.'], 200);
        } else {
            return response()->json(['status'=>'0','message' => 'Error occured in Product Category update.'], 422);
        }
    }

 
}