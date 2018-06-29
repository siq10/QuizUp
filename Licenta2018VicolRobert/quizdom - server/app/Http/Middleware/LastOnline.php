<?php

namespace App\Http\Middleware;
use Faker\Provider\DateTime;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

use Closure;

class LastOnline
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */

    public function handle($request, Closure $next)
    {
        if(\JWTAuth::getToken())
        {
            $userid = \JWTAuth::toUser()->id;
        }
        else
            $userid = \Auth::id();
        $current_time = Carbon::now();
        $time["month"] = $current_time->format('F');
        $time["day"] = $current_time->day;
        Cache::forever($userid."time", $time);
        return $next($request);
    }
}
