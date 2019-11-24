<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/users', function (Request $request) {
//     return $request->user();
// });


Route::get('state', 'AuthController@state');
Route::group([
    'prefix' => 'auth'
], function () {
    Route::post('login', 'AuthController@login');
    Route::post('signup', 'AuthController@signup');
    Route::get('verify_bvn/{id?}', 'AuthController@verifyBVN');
    Route::get('disable/otp', 'AuthController@disableOtp');
    Route::get('verify/disabled/otp/{otp?}', 'AuthController@verifyDisableOtp');
    Route::get('activate/{userId}/{activationCode}', 'AuthController@getActivate');
    Route::post('reset_password', 'AuthController@postChangePassword');
    Route::group([
      'middleware' => ['auth:api','role:administrator|customer']
    ], function() {
        Route::get('logout', 'AuthController@logout');
        Route::get('user', 'AuthController@user');
    });
});
Route::group([
    'middleware' => ['auth:api','role:administrator|customer']
  ], function() {
    Route::get('users', 'UsersController@index');
    Route::get('user/{id?}', 'UsersController@show')->middleware('permission:users.show');
    Route::delete('user/{id?}', 'UsersController@destroy');
    Route::post('user/{id?}', 'UsersController@update');
});

Route::group([
    'middleware' => ['auth:api','role:administrator|customer']
  ], function() {
    Route::get('customers', 'CustomerController@index');
    Route::get('customer/{id?}', 'CustomerController@show');
    Route::delete('customer/{id?}', 'CustomerController@destroy')->middleware('permission:users.destroy');
    Route::post('customer/{id?}', 'CustomerController@update');
});
