<?php

namespace App\Http\Controllers;

use App\Events\RespondToGameInvitation;
use App\Events\GameInProgress;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use JWTAuth;


class GameController extends Controller
{

    public function getMatch(){
        if(JWTAuth::getToken())
        {
            $user = JWTAuth::toUser();
        }
        else
            $user = \Auth::user();
        if (Cache::has("Game.".$user->id))
        {
//            Cache::pull("Game.".$user->id);
//dd(3);
            $match = Cache::get("Game.".$user->id);

            return response()->json($match);
        }
        else
        {
            $nogame = 1;
            return response()->json(compact("nogame"));
        }
    }

    public function denyGame() {
        if(JWTAuth::getToken())
        {
            $user = JWTAuth::toUser();
        }
        else
            $user = \Auth::user();
        if(Cache::has("Game.".$user->id))
        {
            $match = Cache::pull("Game.".$user->id);
            if(Cache::has("Game.".$match['userid2']))
            {
                Cache::pull("Game.".$match['userid2']);
            }
            $data = array();
            $data["userid2"] = $match['userid2'];
            $data["cancel"] = 1;
            event(new RespondToGameInvitation(null,$data));
            return response()->json("",204);
        }
        else return response()->json(null,410);
    }

    public function acceptGame()
    {
        if(JWTAuth::getToken())
        {
            $user = JWTAuth::toUser();
        }
        else
            $user = \Auth::user();
        if(Cache::has("Game.".$user->id))
        {
            $match = Cache::get("Game.".$user->id);
            $match["accepted1"] = 1;
            Cache::forever("Game.".$user->id,$match);


            if(Cache::has("Game.".$match['userid2']))
            {
                $stranger = Cache::get("Game.".$match['userid2']);
            }
            $stranger["accepted2"] = 1;
            Cache::forever("Game.".$match["userid2"],$stranger);

            $data=array();
            $data["accepted"] = 1;
            $data["userid2"] = $match['userid2'];
            event(new RespondToGameInvitation(null,$data));

            if($stranger["accepted1"]==1)
            {
                $problems = \DB::table("problems")
                    ->join("questions",'problems.question_id','=','questions.id')
                    ->leftJoin('answers','problems.right_id','=','answers.id')
                    ->leftJoin('answers as b1','problems.bad1_id','=','b1.id')
                    ->leftJoin('answers as b2','problems.bad2_id','=','b2.id')
                    ->leftJoin('answers as b3','problems.bad3_id','=','b3.id')
                    ->select('b1.text as t1','b2.text as t2','b3.text as t3','answers.text as ta','questions.text as tq','bad1_id','bad2_id','bad3_id','answers.id as aid','questions.id as qid')
//                    ->where('problems.category_id','=',$request->category_id)
//                    ->where('problems.checked','=',0)
                    //->where('user_problems.answered','=',0)
                    ->inRandomOrder()->limit(5)->get();
                $match["problems"] = $problems;
                $stranger["problems"] = $problems;
                $match["status"]="inProgress";
                $stranger["status"]="inProgress";
                Cache::forever("Game.".$user->id,$match);
                Cache::forever("Game.".$match["userid2"],$stranger);
                $problemsdata = array();
                $problemsdata["problems"] = $problems;
                $problemsdata["id"] = $match["userid2"];
                event(new GameInProgress($problemsdata));
                return response()->json(compact("data","problems"),200);
            }
            return response()->json("",202);
        }
        else return response()->json(null,410);
    }

    public function respondToGameInvitation(Request $request)
    {
        if(JWTAuth::getToken())
        {
            $user = JWTAuth::toUser();
        }
        else
            $user = \Auth::user();
        $userid = $user->id;
        if(Cache::has($userid)) {
            $storage = Cache::get($userid);
            $opponentId = $storage["ids"][$request->index];

            if (Cache::has("Game.".$userid) || Cache::has("Game.".$opponentId))
            {
                /**
                 * UNUL DIN USERI ESTE DEJA IN JOC, AMANA REQUESTUL.
                 * */
                return response()->json("Resource Locked",405);
            }

            $opponentName = $storage["names"][$request->index];
            $data["opponentId"] = $opponentId;
            $data["opponentName"] = $opponentName;
            $opponentTime = $storage["times"][$request->index];
            $notificationGuid = $storage["guids"][$request->index];
        }

    else return response()->json("Data not found",404);
        if($request->accepted==0)
        {

            event(new RespondToGameInvitation(-1,$data));
            return response()->json("Success",200);
        }
        else
        if($request->accepted==1)
        {

            $opponent = \App\Models\User::find($opponentId);

            $match = array();
            $match["status"] = "popup";

            $match["userid1"] = $userid;
            $match["username1"] = $user->name;
            $match["userwidth1"] = $user->imgwidth;
            $match["userheight1"] = $user->imgheight;
            $match["usershape1"] = $user->imgshape;
            $match["userimg1"] = $user->imgpath;

            $match["userid2"] = $opponentId;
            $match["username2"] = $opponentName;
            $match["userimg2"] = $opponent->imgpath;
            $match["userwidth2"] = $opponent->imgwidth;
            $match["userheight2"] = $opponent->imgheight;
            $match["usershape2"] = $opponent->imgshape;

            $match["accepted1"] = 0;
            $match["accepted2"] = 0;
            $match["timestarted"] = Carbon::now();

            Cache::forever("Game.".$userid,$match);

            $stranger = array();
            $stranger["status"] = "popup";

            $stranger["userid2"] = $userid;
            $stranger["username2"] = $user->name;
            $stranger["userwidth2"] = $user->imgwidth;
            $stranger["userheight2"] = $user->imgheight;
            $stranger["usershape2"] = $user->imgshape;
            $stranger["userimg2"] = $user->imgpath;

            $stranger["userid1"] = $opponentId;
            $stranger["username1"] = $opponentName;
            $stranger["userimg1"] = $opponent->imgpath;
            $stranger["userwidth1"] = $opponent->imgwidth;
            $stranger["userheight1"] = $opponent->imgheight;
            $stranger["usershape1"] = $opponent->imgshape;

            $stranger["accepted2"] = 0;
            $stranger["accepted1"] = 0;
            $stranger["timestarted"] = $match["timestarted"];
            Cache::forever("Game.".$opponentId,$stranger);

            event(new RespondToGameInvitation(0,$match));

            return response()->json($match);
        }
    }
}
