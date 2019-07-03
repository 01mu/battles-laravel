<?php

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

Route::get('/', 'HomeController@index');

Route::group(['middleware' => 'cors'], function ()
{
    Route::get('/artist/{channel}/{artist}', 'ArtistController@getArtist');
    Route::get('/artists/{channel}/{page}', 'ArtistController@getChannelArtists');
    Route::get('/battle/{id}', 'BattlesController@getSingleBattle');
    Route::get('/random', 'BattlesController@getRandomBattle');
    Route::get('/channel/{channel}/{order}/{page}', 'BattlesController@getBattles');
    Route::get('/stats', 'StatsController@getStats');
});
