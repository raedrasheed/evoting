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

Route::get('/', function () {
    return view('welcome');
});


//Auth::routes();
Auth::routes([
  'register' => false, // Registration Routes...
  'reset' => false, // Password Reset Routes...
  'verify' => false, // Email Verification Routes...
]);

Route::get('/home', 'HomeController@index')->name('home');


Route::get('/voteCards', 'HomeController@voteCards')->name('voteCards');

Route::get('/results', 'HomeController@results')->name('results');
Route::get('/logs/{id}', 'HomeController@logs')->name('logs');
Route::get('/deleteLog/{id}', 'HomeController@deleteLog')->name('deleteLog');
Route::get('/clearLogs', 'HomeController@clearLogs')->name('clearLogs');


//Route::get('/settings', 'HomeController@settings')->name('settings');
Route::get('/editSettings', 'HomeController@editSettings')->name('editSettings');

Route::get('/nominees', 'HomeController@nominees')->name('nominees');
Route::get('/addEditNominee/{id}', 'HomeController@addEditNominee')->name('addEditNominee');
Route::get('/deleteNominee/{id}', 'HomeController@deleteNominee')->name('deleteNominee');
Route::post('/saveNominee', 'HomeController@saveNominee')->name('saveNominee');

Route::get('/users', 'HomeController@users')->name('users');
Route::get('/addEditUser/{id}', 'HomeController@addEditUser')->name('addEditUser');
//Route::get('/deleteUser/{id}', 'HomeController@deleteUser')->name('deleteUser');
Route::post('/saveUser', 'HomeController@saveUser')->name('saveUser');

Route::post('/addBlock', 'BlockController@addBlock')->name('addBlock');
Route::get('/blockchainExplorer', 'HomeController@blockchainExplorer')->name('blockchainExplorer');
Route::get('/refineBlockchain', 'BlockController@refineBlockchain')->name('refineBlockchain');

Route::get('/lang/{locale}', 'LocalizationController@index');




