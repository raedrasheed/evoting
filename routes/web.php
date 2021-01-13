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


Route::get('/results', 'HomeController@results')->name('results');
Route::get('/voteCards', 'HomeController@voteCards')->name('voteCards');

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/votingDemo', 'HomeController@votingDemo')->name('votingDemo');

Route::get('/logs/{id}', 'LogController@logs')->name('logs');
Route::get('/deleteLog/{id}', 'LogController@deleteLog')->name('deleteLog');
Route::get('/clearLogs', 'LogController@clearLogs')->name('clearLogs');

//Route::get('/settings', 'HomeController@settings')->name('settings');
//Route::get('/editSettings', 'HomeController@editSettings')->name('editSettings');

Route::get('/nominees', 'NomineeController@index')->name('nominees');
Route::get('/addEditNominee/{id}', 'NomineeController@addEditNominee')->name('addEditNominee');
Route::get('/deleteNominee/{id}', 'NomineeController@deleteNominee')->name('deleteNominee');
Route::post('/saveNominee', 'NomineeController@saveNominee')->name('saveNominee');

Route::get('/nomineeLists', 'NomineeListController@nomineeLists')->name('nomineeLists');
Route::get('/addEditNomineeList/{id}', 'NomineeListController@addEditNomineeList')->name('addEditNomineeList');
Route::get('/deleteNomineeList/{id}', 'NomineeListController@deleteNomineeList')->name('deleteNomineeList');
Route::post('/saveNomineeList', 'NomineeListController@saveNomineeList')->name('saveNomineeList');

Route::get('/users', 'UserController@users')->name('users');
Route::get('/usersAll', 'UserController@usersAll')->name('usersAll');
Route::get('/addEditUser/{id}', 'UserController@addEditUser')->name('addEditUser');
Route::get('/deleteUser/{id}', 'UserController@deleteUser')->name('deleteUser');
Route::post('/saveUser', 'UserController@saveUser')->name('saveUser');

Route::post('/addVote', 'BlockController@addVote')->name('addVote');

Route::get('/buildBlockchain', 'BlockController@buildBlockchain')->name('buildBlockchain');
Route::get('/blockchainExplorer', 'BlockController@blockchainExplorer')->name('blockchainExplorer');
Route::get('/refineBlockchain', 'BlockController@refineBlockchain')->name('refineBlockchain');

Route::get('/lang/{locale}', 'LocalizationController@index');

Route::get('/sendSMSForAll', 'HomeController@sendSMSForAll')->name('sendSMSForAll');
Route::get('/sendSMSToken/{username}', 'HomeController@sendSMSToken')->name('sendSMSToken');







