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


    $app->post('wallet/users/register', 'WalletController@register');
    $app->get('wallet/users/login', 'WalletController@login');

    $app->group(['prefix' => 'v0', 'namespace' => 'App\Http\Controllers'], function($app) {

        $app->get('wallet/users/{id}', 'WalletController@show');
        $app->post('wallet/users/{id}/generate-address', 'WalletController@generateAddress');
        $app->post('wallet/users/{id}/send', 'WalletController@send');
        $app->post('wallet/users/{id}/import-address', 'WalletController@importAddress');
        $app->post('wallet/users/{id}/sign-message', 'WalletController@signMessage');
        $app->put('wallet/users/{id}', 'WalletController@update');

    });

});