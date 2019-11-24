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

Route::group([
    'middleware' => ['auth:api','role:administrator']
  ], function() {
    Route::get('roles', 'RolesController@index');
    Route::post('roles', 'RolesController@store');
    Route::get('role/{id?}', 'RolesController@show');
    Route::delete('role/{id?}', 'RolesController@destroy')->middleware('permission:roles.destroy');
    Route::post('role/{id?}', 'RolesController@update');
});
