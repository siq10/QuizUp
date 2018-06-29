<?php

namespace App\Http\Controllers;
use App\Models\category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function updateName(Request $request)
    {
        $category = category::findOrFail(\Auth::user()->category_id);
        $category->name = $request->name;
        $category->save();
        $status = "Your category's description has been successfully updated!";
        return view('home',compact("status"));
    }

    public function updateDescription(Request $request)
    {
        $category = category::findOrFail(\Auth::user()->category_id);
        $category->description = $request->description;
        $category->save();
        $status = "Your category's description has been successfully updated!";
        return view('home',compact("status"));
    }
}
