<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\admin;
class AdminController extends Controller
{

    public function filesave(Request $request)
    {
        $path = $request->file->storeAs('Pictures', \Auth::id());

        $status = "Portrait successfuly updated!";
        return view("home",compact("status"));
    }

    public function urlsave(Request $request)
    {
        if (pathinfo($request->url, PATHINFO_EXTENSION) == 'jpg')
        {
            copy($request->url, 'Pictures/' . \Auth::id());
            $status = "Portrait successfuly updated!";
        }
        else
            $status='Err';
        return view("home",compact("status"));
    }

    public function editName(Request $request)
    {
        $admin = admin::findOrFail(\Auth::id());
//        \DB::table('admins')
//            ->where('id', \Auth::id())
//            ->update(['name' => $request->name]);
        $validator = \Validator::make($request->all(), [
            'name' => 'required|unique|min:3'
        ]);
        if ($validator->fails())
        {
            $status = "Err:Name didn't meet the requirements (minimun 3 letters, required)";
            return view('home',compact('status'));
        }

        if ($validator->fails()) {
            return redirect('post/create')
                ->withErrors($validator)
                ->withInput();
        }

        $admin->name = $request->name;
        $admin->save();
        $status="Your name has been successfully changed!";
        return view('home',compact('status'));

    }

    public function editAdminDescription(Request $request)
    {
        $admin = admin::findOrFail(\Auth::id());
        $admin->description = $request->description;
        $admin->save();
        $status="Your description has been successfully changed!";
        return view('home',compact('status'));
    }
}
