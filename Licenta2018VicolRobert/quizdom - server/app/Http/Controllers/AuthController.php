<?php

namespace App\Http\Controllers;

use App\Models\onesignal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Tymon\JWTAuth\Exceptions\JWTException;
//use Tymon\JWTAuth\Facades\JWTAuth;
use JWTAuth;
class AuthController extends Controller
{

    public function authenticate(Request $request)
    {
        // grab credentials from the request
        $credentials = $request->only('name', 'password');
        try {
            // attempt to verify the credentials and create a token for the user
            \Config::set('auth.providers.users.model', \App\Models\User::class);

            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'invalid_credentials'], 401);
            }
        } catch (JWTException $e) {
            // something went wrong whilst attempting to encode the token
            return response()->json(['error' => 'could_not_create_token'], 500);
        }
        // all good so return the token
        $user = JWTAuth::toUser($token);
        $device = \App\Models\onesignal::find($request->device);
        if($device)
        {
            if($device->user_id!=$user->id)
            {
                $device->user_id=$user->id;
                $device->save();
            }

        }
        else{
            $device = new \App\Models\onesignal;
            $device->id = $request->device;
            $device->user_id = $user->id;
            $device->save();
        }
        return response()->json(compact('token', 'user'));
    }

    public function getAuthenticatedUser()
    {
        try {

            if (! $user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['user_not_found'], 404);
            }

        } catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {

            return response()->json(['token_expired'], $e->getStatusCode());

        } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {

            return response()->json(['token_invalid'], $e->getStatusCode());

        } catch (Tymon\JWTAuth\Exceptions\JWTException $e) {

            return response()->json(['token_absent'], $e->getStatusCode());

        }

        // the token is valid and we have found the user via the sub claim
        return response()->json(compact('user'));
    }
}