<?php

$app->group(['prefix' => 'v0', 'namespace' => 'App\Http\Controllers'], function($app) {

    $app->get('transactions', 'TransactionController@index');
    $app->get('transactions/{hash}', 'TransactionController@show');


    $app->get('blocks', 'BlockController@index');
    $app->get('blocks/{hash}', 'BlockController@show');


    $app->get('addresses/{addr}', 'AddressController@show');
    $app->get('addresses/{addr}/validate', 'AddressController@_validate');


    $app->get('info', 'InfoController@index');


    $app->get('verify-message', 'VerifyMessageController@index');


    $app->post('wallet/users/register', 'AuthController@register');
    $app->get('wallet/users/login', 'AuthController@login');

    $app->group(['prefix' => 'v0', 'namespace' => 'App\Http\Controllers', 'middleware' => 'auth'], function($app) {

        $app->get('wallet/users/{id}', 'UserController@show');
        $app->post('wallet/users/{id}/generate-address', 'UserController@generateAddress');
        $app->post('wallet/users/{id}/send', 'UserController@send');
        $app->post('wallet/users/{id}/import-address', 'UserController@importAddress');
        $app->post('wallet/users/{id}/sign-message', 'UserController@signMessage');
        $app->put('wallet/users/{id}', 'UserController@update');

    });

});