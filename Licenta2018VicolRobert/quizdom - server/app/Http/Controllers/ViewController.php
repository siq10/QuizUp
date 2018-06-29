<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ViewController extends Controller
{
    public function adminSettings()
    {
        $admin = \Auth::id();
        if($admin)
        $user = \App\Models\admin::find($admin);
        else return redirect('back');
        if (file_exists('../storage/app/Pictures/' . $admin)) {
            $portrait = 1;
        }
        else
        {
            $portrait = 0;
        }

        $adminCategory = \DB::table("admins")
            ->join("categories",'category_id','=','categories.id')
            ->select('categories.name','categories.description')
            ->where('categories.id','=',\Auth::user()->category_id)
            ->get();

        $categoryName = $adminCategory[0]->name;
        $categoryDescription = $adminCategory[0]->description;
        return view("AdminSettings", compact("admin", "portrait",'user','categoryDescription','categoryName'));
    }

    public function pictureForm($choice)
    {
        return view("PictureForm",compact("choice"));
    }

    public function adminName()
    {
        $adminID = \Auth::id();
        if($adminID)
            $admin = \App\Models\admin::find($adminID);
        else return redirect('back');
        $admin = $admin->name;
        return view("NameForm",compact('admin'));
    }

    public function adminDescription($adminID)
    {
//        $adminID = \Auth::id();
//        if($adminID)
            $admin = \App\Models\admin::find($adminID);
            return view("AdminDescriptionForm",compact('admin'));

    }

    public function categorySettings()
    {
        $admin = \DB::table("admins")
            ->join("categories",'category_id','=','categories.id')
            ->select('categories.name','categories.description')
            ->where('categories.id','=',\Auth::user()->category_id)
            ->get();
        $categoryName = $admin[0]->name;
        $categoryDescription = $admin[0]->description;
        return view("CategoryPanel",compact('categoryDescription','categoryName'));
    }

    public function categoryName()
    {
        $adminCategory = \DB::table("admins")
            ->join("categories",'category_id','=','categories.id')
            ->select('categories.name')
            ->where('categories.id','=',\Auth::user()->category_id)
            ->get();
        $categoryName = $adminCategory[0]->name;
        return view('CategoryNameForm',compact("categoryName"));
    }

    public function categoryDescription()
    {
        $adminCategory = \DB::table("admins")
            ->join("categories",'category_id','=','categories.id')
            ->select('categories.description')
            ->where('categories.id','=',\Auth::user()->category_id)
            ->get();
        $categoryDescription = $adminCategory[0]->description;
        return view('CategoryDescriptionForm',compact("categoryDescription"));
    }
}
