<?php

$namespace = 'Hwacom\ClientSso\Http\Controllers';

Route::group(['namespace' => $namespace,], function () {
    Route::get('/callback', 'SSOController@callback')->middleware('web')->name('callback');
});
