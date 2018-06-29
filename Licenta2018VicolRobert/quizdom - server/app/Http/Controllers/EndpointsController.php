<?php

namespace App\Http\Controllers;

use App\Events\QuickRoom;

use App\Models\answer;
use App\Models\question;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use JWTAuth;
use Illuminate\Http\Request;
use \App\Models\user_problem;
use \App\Models\problem;


class EndpointsController extends Controller
{

    public function verifyUser()
    {
        if(JWTAuth::getToken())
        {
            $response = JWTAuth::toUser()->id;
            return response()->json(compact("response"));
        }
        else {
            $response = 0;
            return response()->json(compact("response"));
        }
    }

    public  function  addImage(Request $request)
    {
        if (JWTAuth::getToken()) {
            $user = JWTAuth::toUser();

        } else
            $user = \Auth::user();

//        dd($_FILES);
        if ($user->imgpath != "")
        {
//            File::delete(explode("/",$user->imgpath)[1]);
            $file_path = $user->imgpath;
        }

        if($request->hasFile('image')) {
            $file = $request->file('image');
            if($file->getClientOriginalExtension() =="png" ||
                $file->getClientOriginalExtension() =="jpg" ||
                $file->getClientOriginalExtension() =="jpeg" ||
                $file->getClientOriginalExtension() =="bmp" ||
                $file->getClientOriginalExtension() =="gif")
            {
                $path = $file->store("avatars");
                $user->imgpath = $path;
                $user->save();
                if (isset($file_path)) {
                    unlink($file_path);
                }
                $ok=1;
                if(Cache::has("queuedList"))
                {
                    $cachedList = Cache::get("queuedList");
                    if(isset($cachedList[$user->id]))
                    {
                        $cachedList[$user->id]["imgpath"] = $user->imgpath;
                        Cache::forever("queuedList",$cachedList);
                    }
                }
            }
            else
            {
                $ok="File extension is not supported!";
                $path = "";
            }

        }
        else{
            $ok = "File was not successfully uploaded!";
        }
        return response()->json(compact("ok","path"));
    }

    public function storeImageSize(Request $request)
    {
        if(JWTAuth::getToken())
        {
            $user = JWTAuth::toUser();
        }
        else
            $user = \Auth::user();

        $user->imgwidth = $request->width;
        $user->imgheight = $request->height;
        $user->imgshape = $request->class;
        $width = $request->width;
        $height = $request->height;
        $shape = $request->class;
        $imgpath = $user->imgpath;
        $user->save();
        if(Cache::has("queuedList"))
        {
            $cachedList = Cache::get("queuedList");
            if(isset($cachedList[$user->id]))
            {
                $cachedList[$user->id]["imgwidth"] = $user->imgwidth;
                $cachedList[$user->id]["imgheight"] = $user->imgheight;
                $cachedList[$user->id]["imgshape"] = $user->imgshape;
                Cache::forever("queuedList",$cachedList);

            }
        }
        return response()->json(compact("width","height","shape","imgpath"),200);
    }


    public function editUser(Request $request) {
        if(JWTAuth::getToken())
        {
            $user = JWTAuth::toUser();
        }
        else
            $user = \Auth::user();
        if($request->name!="")
        {
            $user->name = $request->name;
            if(Cache::has("queuedList"))
            {
                $cachedList = Cache::get("queuedList");
                if(isset($cachedList[$user->id]))
                {
                    $cachedList[$user->id]["name"] = $user->name;
                    Cache::forever("queuedList",$cachedList);
                }
            }
        }
        if($request->email!="")
        {
            $user->email = $request->email;
        }
        if(bcrypt($request->oldpassword)==$user->password)
        {
            $user->password=bcrypt($request->newpassword);
        }
        $user->save();
        $ok=1;
        return response()->json($user);
    }

    public function getQueuedList(Request $request)
    {
        if(JWTAuth::getToken())
        {
            $user = JWTAuth::toUser();
        }
        else
            $user = \Auth::user();
        if (Cache::has("queuedList"))
        {
//            Cache::pull("queuedList");
//            dd(2);
            $cachedList = Cache::get("queuedList");
            if(!isset($cachedList[$user->id]))
            {
                $register = array('id'=>$user->id,"imgpath"=>$user->imgpath,"imgwidth"=>$user->imgwidth,"imgheight"=>$user->imgheight,"imgshape"=>$user->imgshape,"name" => $user->name,"wins" => $user->wins, "losses" => $user->losses);
                $cachedList[$user->id] = $register;
                Cache::forever("queuedList",$cachedList);
                event(new QuickRoom($register,1));

            }
        }
        else{
                $cachedList = array();
                $register = array('id'=>$user->id,"imgpath"=>$user->imgpath,"imgwidth"=>$user->imgwidth,"imgheight"=>$user->imgheight,"imgshape"=>$user->imgshape,"name" => $user->name,"wins" => $user->wins, "losses" => $user->losses);
                $cachedList[$user->id] = $register;
                Cache::forever("queuedList",$cachedList);
        }
        return response()->json(compact("cachedList"));

    }

    public function leaveRoom()
    {
        if(JWTAuth::getToken())
        {
            $user = JWTAuth::toUser();
        }
        else
            $user = \Auth::user();
        $cachedList = Cache::get("queuedList");
//        dd($cachedList);
//        event(new QuickRoom($cachedList[$user->id],0));

        broadcast(new QuickRoom($cachedList[$user->id],0))->toOthers();
        unset($cachedList[$user->id]);
        Cache::forever("queuedList",$cachedList);
        return response()->json(['id' => $user->id], 202);

    }


    public function getPlayers()
    {
        if(JWTAuth::getToken())
        {
            $userid = JWTAuth::toUser()->id;
        }
        else
            $userid = \Auth::id();
        $users = \App\Models\User::where('id','!=',$userid)->get();
        foreach($users as $user)
        {
            $check = Cache::has($user->id."time");
            if($check)
                {
                    $user["lastseen"] = Cache::get($user->id."time");
                }
            else
            {
                $unknown["day"] = "?";
                $unknown["month"] = "Unknown";
                $user["lastseen"]=$unknown;
            }
        }
        return response()->json(compact("users"));
    }
    public function buyQuestion(Request $request) //works
    {
        if(JWTAuth::getToken())
        {
            $userid = JWTAuth::toUser()->id;
        }
        else
            $userid = \Auth::id();
        $user = \App\Models\User::find($userid);
        $test = $user->tokens - $request->amount;
        if($test >= 0)
        {

            $ownedProblems = \DB::table('user_problems')
                ->join('problems','user_problems.problem_id','=','problems.id')
                ->where('user_id',$userid)
                ->where('problems.category_id',$request->category_id)
                ->count();
            $problems = problem::where('category_id',$request->category_id)->count();
            $remainingProblems = $problems - $ownedProblems;
            if ($remainingProblems >= $request->amount)
            {
                $package = \DB::table('user_problems')
                    ->rightJoin('problems','user_problems.problem_id','=','problems.id')
                    ->select('problems.id')
                    ->where('problems.category_id','=',$request->category_id)
                    ->where('user_problems.user_id','!=',$userid)
                    ->take($request->amount)
                    ->get();
                foreach($package as $p)
                {
                    $up = new user_problem;
                    $up->user_id = $userid;
                    $up->problem_id = $p->id;
                    $up->save();
                }
                $ok=1;
                $status = "Problems successfully added!";
                $user->tokens = $test;
                $user->save();
            }
            else
            {
                $ok = 0;
                $status = "There are not enough problems left with that category!";
            }
        }
        else
        {
            $ok = 0;
            $status = "You don't have enough tokens!";
        }
        return response()->json(compact("ok","status"));
    }

    public function getAccountInfo()
    {
        if(\JWTAuth::getToken())
        {
            $userid = \JWTAuth::toUser()->id;
        }
        else
            $userid = \Auth::id();
        $usr = \App\Models\User::find($userid);
        $categories = \DB::table("user_problems")
            ->join("problems",'user_problems.problem_id','=','problems.id')
            ->join("categories",'problems.category_id','=','categories.id')
            ->select('categories.name')
            ->where('user_problems.user_id',$userid)
            ->distinct()
            ->get();

        $DTO['name'] = $usr->name;
        $DTO['email'] = $usr->email;
        $DTO['tokens'] = $usr->tokens;
        $DTO["imgpath"] = $usr->imgpath;
        $DTO["imgwidth"] = $usr->imgwidth;
        $DTO["imgheight"] = $usr->imgheight;
        $DTO["imgshape"] = $usr->imgshape;
        $DTO['active'] = $categories;
        return response()->json(compact('DTO'));
    }

    public function canAsk($catid)
    {
        if(\JWTAuth::getToken())
        {
            $userid = \JWTAuth::toUser()->id;
        }
        else
            $userid = \Auth::id();
        $added = problem::where('owner_id',$userid)->where('category_id',$catid)->count();
        $answeredright = \DB::table("user_problems")
        ->join("problems",'user_problems.problem_id','=','problems.id')
        ->select('user_problems.id')
        ->where('user_problems.user_id',$userid)
        ->where('user_problems.answered',1)
            ->where('category_id',$catid)
        ->count();
        $value = $answeredright - $added*10;
        return response()->json(compact("value"));
    }

    public function cancreateQ()
    {
        if(\JWTAuth::getToken())
        {
            $userid = \JWTAuth::toUser()->id;
        }
        else
            $userid = \Auth::id();
        $categories = \DB::table("categories")
        ->select("id","name")
        ->get();
        $ret = [];

        foreach ($categories as $cat) {
            $added = problem::where('owner_id', $userid)->where('category_id', $cat->id)->count();
            $answeredright = \DB::table("user_problems")
                ->join("problems", 'user_problems.problem_id', '=', 'problems.id')
                ->select('user_problems.id')
                ->where('user_problems.user_id', $userid)
                ->where('user_problems.answered', 1)
                ->where('category_id', $cat->id)
                ->count();
            $value = $answeredright - $added * 10;
            if ($value >= 10) {
                $dec = 1;
            } else
                $dec = 0;
            $ret[] =["id"=>$cat->id,"name"=>$cat->name,"val"=>$dec];
        }

        return response()->json(compact("ret"));
    }

    public function getAvailableCategories()
    {
        if(\JWTAuth::getToken())
        {
            $userid = \JWTAuth::toUser()->id;
        }
        else
            $userid = \Auth::id();
        $categories = \DB::table("user_problems")
            ->join("problems",'user_problems.problem_id','=','problems.id')
            ->join("categories",'problems.category_id','=','categories.id')
            ->select('categories.id','categories.name')
            ->where('user_problems.user_id',$userid)
            ->where('user_problems.answered',0)
            ->distinct()
            ->get();
        if (count($categories)) {
            $ok = 1;
            return response()->json(compact("categories", "ok"));
        }
        else
            $ok = 0;
        return response()->json(compact("ok"));
    }

    public function getUserOPCategoriesAndProblems()
    {
        if (\JWTAuth::getToken()) {
            $userid = \JWTAuth::toUser()->id;
        }
        else
            $userid = \Auth::id();
        $categories = \DB::table("problems")
            ->join("categories",'problems.category_id','=','categories.id')
            ->select('categories.id','categories.name')
            ->where('problems.owner_id',$userid)
            ->distinct()
            ->get();

        $problems = \DB::table("user_problems")
            ->join("problems",'user_problems.problem_id','=','problems.id')
            ->join("questions",'problems.question_id','=','questions.id')
            ->select('user_problems.id as pid','problems.mistakes','problems.answers','questions.text as tq','questions.id as qid',"problems.category_id as catid")
            ->where('problems.owner_id','=',$userid)
            ->where('problems.checked','=',0)
            //->where('user_problems.answered','=',0)
            ->get();
        if(count($problems))
            $ok=1;
        else $ok = 0;
        return response()->json(compact("categories","problems","ok"));
    }

    public function getUserBPCategoriesAndProblems()
    {
        if (\JWTAuth::getToken()) {
            $userid = \JWTAuth::toUser()->id;
        }
        else
            $userid = \Auth::id();
        $categories = \DB::table("problems")
            ->join("categories",'problems.category_id','=','categories.id')
            ->join("user_problems","problems.id","=","user_problems.problem_id")
            ->select('categories.id','categories.name')
            ->where('user_problems.user_id',$userid)
            ->distinct()
            ->get();

        $problems = \DB::table("user_problems")
            ->join("problems",'user_problems.problem_id','=','problems.id')
            ->join("questions",'problems.question_id','=','questions.id')
            ->leftJoin('answers','problems.right_id','=','answers.id')
            ->leftJoin('answers as b1','problems.bad1_id','=','b1.id')
            ->leftJoin('answers as b2','problems.bad2_id','=','b2.id')
            ->leftJoin('answers as b3','problems.bad3_id','=','b3.id')
            ->select('user_problems.id as pid','b1.text as t1','b2.text as t2','b3.text as t3','answers.text as ta','questions.text as tq','problems.category_id as catid')
            ->where('user_problems.user_id','=',$userid)
            ->where('problems.checked','=',0)
            ->where('user_problems.answered','!=',0)
            ->get();
//        dd($problems);
        if(count($problems))
            $ok=1;
        else $ok = 0;
        return response()->json(compact("categories","problems","ok"));
    }


    public function getAllSolvedProblems()
    {
        if(\JWTAuth::getToken())
        {
            $userid = \JWTAuth::toUser()->id;
        }
        else
            $userid = \Auth::id();
        $problems = \DB::table("user_problems")
            ->join("problems",'user_problems.problem_id','=','problems.id')
            ->select('user_problems.id')
            ->where('user_problems.user_id',$userid)
            ->whereIn('user_problems.answered',[1,2])
            ->count();
        return response()->json(compact("problems"));
    }

    public function getAvailableProblems(Request $request)
    {
        if(\JWTAuth::getToken())
        {
            $userid = \JWTAuth::toUser()->id;
        }
        else
            $userid = \Auth::id();
        $problems = \DB::table("user_problems")
            ->join("problems",'user_problems.problem_id','=','problems.id')
            ->join("questions",'problems.question_id','=','questions.id')
            ->leftJoin('answers','problems.right_id','=','answers.id')
            ->leftJoin('answers as b1','problems.bad1_id','=','b1.id')
            ->leftJoin('answers as b2','problems.bad2_id','=','b2.id')
            ->leftJoin('answers as b3','problems.bad3_id','=','b3.id')
            ->select('user_problems.id as pid','problems.mistakes','problems.answers','b1.text as t1','b2.text as t2','b3.text as t3','answers.text as ta','questions.text as tq','bad1_id','bad2_id','bad3_id','answers.id as aid','questions.id as qid')
            ->where('user_problems.user_id','=',$userid)
            ->where('problems.category_id','=',$request->category_id)
            ->where('problems.checked','=',0)
            //->where('user_problems.answered','=',0)
            ->get();
        if (count($problems)) {
            $ok = 1;
            return response()->json(compact("problems", "ok"));
        }
        else
            $ok = 0;
        return response()->json(compact("ok"));

    }

    public function getUnansweredProblems($id) //works
    {
        if(\JWTAuth::getToken())
        {
            $userid = \JWTAuth::toUser()->id;
        }
        else
            $userid = \Auth::id();
        $problems = \DB::table("user_problems")
            ->join("problems",'user_problems.problem_id','=','problems.id')
            ->join("questions",'problems.question_id','=','questions.id')
            ->leftJoin('answers','problems.right_id','=','answers.id')
            ->leftJoin('answers as b1','problems.bad1_id','=','b1.id')
            ->leftJoin('answers as b2','problems.bad2_id','=','b2.id')
            ->leftJoin('answers as b3','problems.bad3_id','=','b3.id')
            ->select('user_problems.id as pid','problems.mistakes','problems.answers','b1.text as t1','b2.text as t2','b3.text as t3','answers.text as ta','questions.text as tq','bad1_id','bad2_id','bad3_id','answers.id as aid','questions.id as qid')
            ->where('user_problems.user_id','=',$userid)
            ->where('problems.category_id','=',$id)
            ->where('problems.checked','=',0)
            ->where('user_problems.answered','=',0)
            ->get();
        if (count($problems)) {
            $ok = 1;
            return response()->json(compact("problems", "ok"));
        }
        else
            $ok = 0;
        return response()->json(compact("ok"));
    }


    public function getAnsweredProblems(Request $request) //works
    {
        if(\JWTAuth::getToken())
        {
            $userid = \JWTAuth::toUser()->id;
        }
        else
            $userid = \Auth::id();
        $problems = \DB::table("user_problems")
            ->join("problems",'user_problems.problem_id','=','problems.id')
            ->join("questions",'problems.question_id','=','questions.id')
            ->leftJoin('answers','problems.right_id','=','answers.id')
            ->leftJoin('answers as b1','problems.bad1_id','=','b1.id')
            ->leftJoin('answers as b2','problems.bad2_id','=','b2.id')
            ->leftJoin('answers as b3','problems.bad3_id','=','b3.id')
            ->select('user_problems.id as pid','problems.mistakes','problems.answers','b1.text','b2.text','b3.text','answers.text','questions.text','bad1_id','bad2_id','bad3_id','answers.id as aid','questions.id as qid')
            ->where('user_problems.user_id','=',$userid)
            ->where('problems.category_id','=',$request->category_id)
            ->where('problems.checked','=',0)
            ->where('user_problems.answered','=',1)
            ->get();
        if (count($problems)) {
            $ok = 1;
            return response()->json(compact("problems", "ok"));
        }
        else
            $ok = 0;
        return response()->json(compact("ok"));
    }
    public function getTokens()
    {
        if (\JWTAuth::getToken()) {
            $userid = \JWTAuth::toUser()->id;
        } else
            $userid = \Auth::id();
        $tokens = \JWTAuth::toUser()->tokens;
        return response()->json(compact("tokens"));
    }

    public function shopInfo(){
        if (\JWTAuth::getToken()) {
            $userid = \JWTAuth::toUser()->id;
        } else
            $userid = \Auth::id();
        $tokens = \JWTAuth::toUser()->tokens;
        $allcategories = \DB::table("categories")
            ->select("id", "name")
            ->get();
        return response()->json(compact("tokens","allcategories"));
    }

    public function addProblem(Request $request)
    {
        if(\JWTAuth::getToken())
        {
            $userid = \JWTAuth::toUser()->id;
        }
        else
            $userid = \Auth::id();
            $problem = new problem;
            $problem->owner_id = $userid;
            $problem->category_id = $request->category_id;
            $problem->answers = 0;
            $problem->mistakes = 0;

            $question = new question;
            $question->text = $request->question;
            $question->save();
            $problem->question_id = $question->id;

            $bad1 = new answer;
            $bad1->text = $request->bad1;
            $bad1->save();
            $problem->bad1_id = $bad1->id;

            if($request->bad2!="") {
                $bad2 = new answer;
                $bad2->text = $request->bad2;
                $bad2->save();
                $problem->bad2_id = $bad2->id;
            }


            if($request->bad3!="") {
                $bad3 = new answer;
                $bad3->text = $request->bad3;
                $bad3->save();
                $problem->bad3_id = $bad3->id;
            }

            $right = new answer;
            $right->text = $request->right;
            $right->save();
            $problem->right_id = $right->id;

            $problem->save();
            $user = User::find($userid);
            $user->tokens-=10;
            $user->save();
            $ok = 1;

        return response()->json(compact('ok'));

    }

    public function answerProblem(Request $request)
    {
        if (\JWTAuth::getToken()) {
            $userid = \JWTAuth::toUser()->id;
        } else
            $userid = \Auth::id();

        $user_problem = \App\Models\user_problem::find($request->id);
        $user_problem->answered = $request->answered;

        if ($request->reported == 1)
            $user_problem->reported = 1;
        $user_problem->save();

        $problem = \App\Models\problem::find($user_problem->problem_id);
        if ($request->answered == 1)
        {
            $usr = \App\Models\User::find($userid);
            $usr->tokens++;
            $usr->save();
            $problem->answers++;
        }
        else
        if($request->answered == 2)
        {
            $problem->answers++;
            $problem->mistakes++;
        }
        $problem->save();
        $ok = 1;
        return response()->json(compact('ok'));
    }




}
