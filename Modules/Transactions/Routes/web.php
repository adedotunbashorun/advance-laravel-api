<?php

Route::group(['prefix' => 'transactions'], function()
{
    Route::get('/', 'TransactionsController@index');
});
