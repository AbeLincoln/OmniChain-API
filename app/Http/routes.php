<?php

$app->group(['prefix' => 'v0/transactions', 'namespace' => 'App\Http\Controllers'], function($app) {

    $app->get('/', 'TransactionController@index');

    $app->get('/{hash}', 'TransactionController@show');

});

$app->group(['prefix' => 'v0/blocks', 'namespace' => 'App\Http\Controllers'], function($app) {

    $app->get('/', 'BlockController@index');

    $app->get('/{hash}', 'BlockController@show');

});

$app->group(['prefix' => 'v0/addresses', 'namespace' => 'App\Http\Controllers'], function($app) {

    $app->get('/{addr}', 'AddressController@show');

});