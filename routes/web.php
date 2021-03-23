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

use App\Exceptions\CustomException;
use App\Models\User;
use Illuminate\Support\Facades\Route;
use App\Notifications\PingNotification;

Route::get('/', function () {
    return view('welcome');
});

Route::view('/home', 'home');

Route::get('/exception', function(){
    throw new \Exception('This is a Fake Exception, just like News!');
});

Route::get('/custom-config', function(){
    $throw_custom_exception = config('custom.thow_exception');

    if ($throw_custom_exception){
        throw new CustomException($throw_custom_exception);
    }

    dd($throw_custom_exception);
});

Route::get('/server-error', function(){
    abort(500);
});

Route::get('/notification', function () {
    $user = User::find(1);

    return (new PingNotification())
        ->toMail($user);
});