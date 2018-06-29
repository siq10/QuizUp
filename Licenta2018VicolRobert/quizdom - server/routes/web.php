<?php
//header('Access-Control-Allow-Origin: http://localhost:8080');

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::group(['middleware' => 'csrf'], function () {
    Route::get('/', function () {
        return view('welcome');
    });

    Auth::routes();

    Route::get('/home', 'HomeController@index')->name('home');

    Route::group(['prefix' => 'account'], function () {
        Route::get('/settings', 'ViewController@adminSettings')->name('settings');

        Route::get('/name', 'ViewController@adminName')->name('adminname');
        Route::put('/name/save', "AdminController@editName")->name("editadminname");

        Route::get('/description/{adminID}', 'ViewController@adminDescription')->name('admindescription');
        Route::put('/description/save', "AdminController@editAdminDescription")->name("editadmindescription");


        Route::get('/category', 'ViewController@categorySettings')->name('categorysettings');
        Route::get('/category/name', 'ViewController@categoryName')->name('categoryname');
        Route::get('/category/description', 'ViewController@categoryDescription')->name('categorydescription');
        Route::put('/category/save/name', 'CategoryController@updateName')->name('editcategoryname');
        Route::put('/category/save/description', 'CategoryController@updateDescription')->name('editcategorydescription');

        Route::get('/picture/{choice}', 'ViewController@pictureForm')->name('picture');
        Route::post('/picture/save', 'AdminController@filesave')->name('filesave');
        Route::post('/picture/url', 'AdminController@urlsave')->name('urlsave');

    });
});
        Route::post('/api/authenticate', 'AuthController@authenticate');
        Route::post('/api/register', 'UsersController@create');
        Route::get('/api/logged',"EndpointsController@verifyUser");
            Route::group(['prefix' => 'api','middleware' => 'lastseen'], function () {
                Route::get('categories', 'EndpointsController@getAvailableCategories');
                Route::get('pcount', 'EndpointsController@getAllSolvedProblems');
                Route::get('/available', 'EndpointsController@getAvailableProblems');
                Route::get('notifications',"NotificationsController@getNotifications");
                Route::get('/answered', 'EndpointsController@getAnsweredProblems');
                Route::get('/unanswered/{id}', 'EndpointsController@getUnansweredProblems');
                Route::get('/account', 'EndpointsController@getAccountInfo');
                Route::get('/canask/{catid}', 'EndpointsController@canAsk');
                Route::get('/caniadd', 'EndpointsController@cancreateQ');
                Route::get('/tokens', 'EndpointsController@getTokens');
                Route::get("shopinfo","EndpointsController@shopInfo");

                Route::get("game/status","GameController@getMatch");
                Route::delete('game/cancel',"GameController@denyGame");
                Route::put('game/accept',"GameController@acceptGame");


                Route::put('solve','EndpointsController@answerProblem');
                Route::put('user/edit',"EndpointsController@editUser");

                Route::post('/buy', 'EndpointsController@buyQuestion');
                Route::post("submit","EndpointsController@addProblem");
                Route::post("purchase","EndpointsController@buyQuestion");
                Route::post("submit","EndpointsController@addProblem");


                Route::post('image/add', 'EndpointsController@addImage');
                Route::put('image/size', "EndpointsController@storeImageSize");


                Route::post('challenge/send', 'NotificationsController@sendChallenge');
                Route::post('game', 'GameController@respondToGameInvitation');

                Route::delete("notifications/delete", 'NotificationsController@respondToChallenge');
                Route::delete("room","EndpointsController@leaveRoom");


                Route::group(['prefix'=>'user'],function() {
                    Route::get('OP',"EndpointsController@getUserOPCategoriesAndProblems");
                    Route::get('BP',"EndpointsController@getUserBPCategoriesAndProblems");

                });
                Route::get('players',"EndpointsController@getPlayers");
                Route::get('queued',"EndpointsController@getQueuedList");


            });
