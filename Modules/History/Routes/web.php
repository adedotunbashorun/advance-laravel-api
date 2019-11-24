<?php

Route::group(['prefix' => 'history'], function()
{
    Route::get('/', 'HistoryController@index');
});
