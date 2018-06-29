<?php

namespace App\Http\Controllers;

use App\Events\NotifyUser;
use App\Events\RespondToGameInvitation;
use App\Events\StartGame;
use Illuminate\Http\Request;
use \App\Models\onesignal;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class NotificationsController extends Controller
{

    /**
     * @param Request $request
     */
    public function respondToChallenge(Request $request)
    {
        if(\JWTAuth::getToken())
        {
            $userid = \JWTAuth::toUser()->id;
        }
        else
            $userid = \Auth::id();


        if(Cache::has($userid))
        {
            $storage = Cache::get($userid);

            $opponentId = $storage["ids"][$request->index];
            $opponentName = $storage["names"][$request->index];
            $opponentTime = $storage["times"][$request->index];
            $notificationGuid = $storage["guids"][$request->index];

            array_splice($storage["names"],$request->index,1);
            array_splice($storage["ids"],$request->index,1);
            array_splice($storage["times"],$request->index,1);
            array_splice($storage["guids"],$request->index,1);

            Cache::forever($userid, $storage);
            $ok=Cache::get($userid);

            return response()->json(compact("ok"));

        }




    }

    public function getNotifications()
    {
        if(\JWTAuth::getToken())
        {
            $userid = \JWTAuth::toUser()->id;
        }
        else
            $userid = \Auth::id();
        if(Cache::has($userid))
        {
            $storage = Cache::get($userid);
            return response()->json(compact("storage"));
        }
        else{
            $storage = 0;
            return response()->json(compact("storage"));
        }
    }
    public function notify($userids,$title,$message){
        $devices = onesignal::whereIn("user_id",$userids)->pluck("id");
        $content = array(
            "en" => $message
        );
        $heading = array(
            "en" => $title
        );
        $devices = $devices->toArray();

        $fields = array(
            'app_id' => "7ceb9b7f-5aa3-448e-8941-01212bbf1a32",
            'include_player_ids' => $devices,
            'data' => array("foo" => "bar"),
            'contents' => $content,
            'headings' => $heading
        );

        $fields = json_encode($fields);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8',
            'Authorization: Basic YjQ0NjgwMmMtODNiZC00ZGIzLTg4MWYtNDkxYWM0MmUyMTkw'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

        $response = curl_exec($ch);
        curl_close($ch);

        return $response;
    }


    public function sendChallenge(Request $request)
    {
        if(\JWTAuth::getToken())
        {
            $userid = \JWTAuth::toUser()->id;
        }
        else
            $userid = \Auth::id();
        $username = \App\Models\User::find($userid)->name;
        $title = "You have been challenged!";
        $message = $username." wants to play with you!";
        $opponent = \App\Models\User::find($request->id);
        if($opponent)
        {
            $this->notify(array($opponent->id),$title,$message);
            $status=1;
            event(new StartGame($userid,$opponent->id));
            $current_time = Carbon::now()->timezone('Europe/Bucharest');
//            dd($current_time);
            $guid = uniqid("id");
            event(new NotifyUser($guid,$userid,$opponent->id,$current_time,$username));
            if(Cache::has($opponent->id))
            {
                $storage = Cache::get($opponent->id);
                array_unshift($storage["ids"],$userid);
                array_unshift($storage["names"],$username);
                array_unshift($storage["times"],$current_time);
                array_unshift($storage["guids"],$guid);
                Cache::forever($opponent->id, $storage);
                /**
                 * Cache Data exists, notification added with name and time.
                 */
            }
            else
            {
                $storage = array();
                $storage["guids"] = array();
                $storage["names"] = array();
                $storage["times"] = array();
                $storage["ids"] = array();
                array_unshift($storage["names"],$username);
                array_unshift($storage["ids"],$userid);
                array_unshift($storage["times"],$current_time);
                array_unshift($storage["guids"],uniqid("id"));
                Cache::forever($opponent->id, $storage);
                /**
                 * Cache Data doesn't exist, it is created and first notification is added with name and time.
                 */
            }

            return response()->json(compact("status"));

        }
        else
        {
            $status=0;
            return response()->json(compact("status"));

        }
    }
}
