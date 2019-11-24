<?php


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



Route::group(['middleware' => ['auth:api','role:administrator'], 'prefix' => 'settings'], function () {

    Route::get('/', ['as' => 'admin.settings.index', 'uses' => 'SettingsController@index']);
    Route::post('/store', ['as' => 'admin.settings.store', 'uses' => 'SettingsController@store']);

    Route::post('/send-test-mail', ['as' => 'ajax.settings.send_test_mail', 'uses' => 'SettingsController@sendTestMail']);
    //Route::put('api/settings', 'SettingsController@deleteImage');
});
