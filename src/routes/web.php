<?php

$namespace = 'Hwacom\ClientSso\Http\Controllers';

Route::group(['namespace' => $namespace,], function () {
    Route::get('/', 'SSOController@index');
});
