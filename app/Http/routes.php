<?php

$app->group(['prefix' => 'v0/', 'namespace' => 'App\Http\Controllers'], function($app) {

    $app->get('', 'InfoController@index');

    $app->group(['prefix' => 'v0/addresses/{addr}', 'namespace' => 'App\Http\Controllers'], function($app) {

        $app->get('', 'AddressController@show');
        $app->get('validate', 'AddressController@val');
        $app->get('validate-message', 'AddressController@validateMessage');

    });

    $app->group(['prefix' => 'v0/blocks', 'namespace' => 'App\Http\Controllers'], function($app) {

        $app->get('', 'BlockController@index');

        $app->group(['prefix' => 'v0/blocks/{hash}', 'namespace' => 'App\Http\Controllers'], function($app) {

            $app->get('', 'BlockController@show');

        });

    });

    $app->group(['prefix' => 'v0/transactions', 'namespace' => 'App\Http\Controllers'], function($app) {

        $app->get('', 'TransactionController@index');

        $app->group(['prefix' => 'v0/transactions/{hash}', 'namespace' => 'App\Http\Controllers'], function($app) {

            $app->get('', 'TransactionController@show');

        });

    });

    $app->group(['prefix' => 'v0/wallet/users', 'namespace' => 'App\Http\Controllers'], function($app) {

        $app->post('register', 'AuthController@register');
        $app->get('login', 'AuthController@login');

        $app->group(['prefix' => 'v0/wallet/users/{id}', 'namespace' => 'App\Http\Controllers', 'middleware' => 'auth'], function($app) {

            $app->get('', 'UserController@show');
            $app->put('', 'UserController@update');
            $app->get('balance', 'UserController@balance');//TODO: Resource or action...hmm

            $app->group(['prefix' => 'v0/wallet/users/{id}/addresses', 'namespace' => 'App\Http\Controllers', 'middleware' => 'auth'], function($app) {

                $app->get('', 'UserAddressController@index');
                $app->post('', 'UserAddressController@create');//generate & import

                $app->group(['prefix' => 'v0/wallet/users/{id}/addresses/{addr}', 'namespace' => 'App\Http\Controllers', 'middleware' => 'auth'], function($app) {

                    $app->delete('', 'UserAddressController@delete');
                    $app->get('sign-message', 'UserAddressController@signMessage');

                });

            });

            $app->group(['prefix' => 'v0/wallet/users/{id}/api-keys', 'namespace' => 'App\Http\Controllers', 'middleware' => 'auth'], function($app) {

                $app->get('', 'UserApiKeyController@index');
                $app->post('', 'UserApiKeyController@create');

                $app->group(['prefix' => 'v0/wallet/users/{id}/api-keys/{aid}', 'namespace' => 'App\Http\Controllers', 'middleware' => 'auth'], function($app) {

                    $app->get('', 'UserApiKeyController@show');
                    $app->put('', 'UserApiKeyController@update');
                    $app->delete('', 'UserApiKeyController@delete');

                });

            });

            $app->group(['prefix' => 'v0/wallet/users/{id}/transactions', 'namespace' => 'App\Http\Controllers', 'middleware' => 'auth'], function($app) {

                $app->get('', 'UserTransactionController@index');
                $app->post('send', 'UserTransactionController@send');

            });

            $app->group(['prefix' => 'v0/wallet/users/{id}/balance', 'namespace' => 'App\Http\Controllers', 'middleware' => 'auth'], function($app) {
    
                $app->get('', 'UserBalanceController@show');//TODO: Resource or action...hmm
    
            });

        });
        
    });

});