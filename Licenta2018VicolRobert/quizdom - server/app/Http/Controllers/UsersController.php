<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UsersController extends Controller
{
    //Endpoints
    public function create(Request $request)
    {
        // validate the request
        $validator = \Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:4'
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        $superuser = \App\Models\superuser::create();
        // create the user
        $data = $request->all();
        $data['tokens'] = 20;
        $data['superuser_id'] = $superuser->id;
        $data['password'] = bcrypt($data['password']);
        $user = \App\Models\User::create($data);
        // return the new resource
        return response()->json($user);
    }

}
