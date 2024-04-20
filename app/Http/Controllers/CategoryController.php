<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::all();
        return response()->json($categories);
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $category = Category::create($data);
        return response()->json($category);
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $id)
    {
        return response()->json($id);
    }



    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $id)
    {
        $message = "Error";

        $id->title = $request->title;
        if($id->save()){
            $message = "Done";
        }
        return response()->json([
            'message'=> $message,
            'category' => $id
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $id)
    {
        $message = "Error";
        if($id->delete()){
            $message = "Done";
        }
        return response()->json([
            'message' => $message
        ]);
    }
}
