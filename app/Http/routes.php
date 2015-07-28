<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/
Route::group(['middleware' => 'auth.basic.once'], function (){
    
    Route::get('sessions/cinema/{id}/{date}', 'SessionsController@GetCinemaSessions');
    Route::get('sessions/movie/{id}/{date}', 'SessionsController@GetMovieSessions');
    Route::get('sessions/date/{date}', 'SessionsController@GetDateSessions');

    Route::get('cinemas/lon/{geo_lon}/lat/{geo_lat}/km/{max_km}', 'CinemasController@GetClosestCinemas');

    Route::resource('movies', 'MoviesController', ['only' => ['index', 'show']]);
    Route::resource('cinemas', 'CinemasController', ['only' => ['index', 'show']]);
    Route::resource('sessions' ,'SessionsController', ['only' => ['index']]);

});

Route::get('/', function () {
    return view('welcome');
});

/*
Route::get('user', ['middleware' => 'auth.basic.once', function() {
    return view('welcome');
}]);
 */
//Route::get('user', ['uses' => 'SessionsController@userauth','middleware'=>'auth.basic.once']);