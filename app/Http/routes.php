<?php
//TODO: Unit testing
$app->group(['prefix' => 'v0/', 'namespace' => 'App\Http\Controllers\v0'], function ($app) {

    $app->get('',   'InfoController@index');

    $app->group(['prefix' => 'v0/addresses/{addr}', 'namespace' => 'App\Http\Controllers\v0\addresses'], function ($app) {

        $app->get(  '',                   'AddressController@show');
        $app->get(  'validate',           'AddressController@val');
        $app->get(  'validate-message',   'AddressController@validateMessage');

    });

    $app->group(['prefix' => 'v0/blocks', 'namespace' => 'App\Http\Controllers\v0\blocks'], function ($app) {

        $app->get(  '',           'BlockController@index');
        $app->get(  '{hash}',     'BlockController@show');

    });

    $app->group(['prefix' => 'v0/transactions', 'namespace' => 'App\Http\Controllers\v0\transactions'], function ($app) {

        $app->get(  '',           'TransactionController@index');
        $app->get(  '{hash}',     'TransactionController@show');

    });

    $app->group(['prefix' => 'v0/users', 'namespace' => 'App\Http\Controllers\v0\users'], function ($app) {

        $app->post( 'register',     'AuthController@register');
        $app->get(  'login',        'AuthController@login');

        $app->group(['prefix' => 'v0/users/{id}', 'namespace' => 'App\Http\Controllers\v0\users', 'middleware' => 'auth'], function ($app) {

            $app->get(  '',       'UserController@show');
            $app->put(  '',       'UserController@update');

            $app->group(['prefix' => 'v0/users/{id}/api-keys', 'namespace' => 'App\Http\Controllers\v0\users\apikeys', 'middleware' => 'auth'], function ($app) {

                $app->get(      '',         'ApiKeyController@index');
                $app->post(     '',         'ApiKeyController@create');
                $app->get(      '{aid}',    'ApiKeyController@show');
                $app->put(      '{aid}',    'ApiKeyController@update');
                $app->delete(   '{aid}',    'ApiKeyController@delete');

            });

            $app->group(['prefix' => 'v0/users/{id}/wallet', 'namespace' => 'App\Http\Controllers\v0\users\wallet', 'middleware' => 'auth'], function ($app) {

                $app->get(  '',     'WalletController@show');//balance / TX stats

                $app->group(['prefix' => 'v0/users/{id}/wallet/addresses', 'namespace' => 'App\Http\Controllers\v0\users\wallet\addresses', 'middleware' => 'auth'], function ($app) {

                    $app->get(      '',             'AddressController@index');
                    $app->post(     '',             'AddressController@create');//generate & import
                    $app->delete(   '{addr}',       'AddressController@delete');
                    $app->get(      '{addr}/sign',  'AddressController@sign');

                });

                $app->group(['prefix' => 'v0/users/{id}/wallet/transactions', 'namespace' => 'App\Http\Controllers\v0\wallet\transactions', 'middleware' => 'auth'], function ($app) {

                    $app->get(  '',         'TransactionController@index');
                    $app->post( 'send',     'TransactionController@send');

                });

            });

        });
        
    });

});

$app->get('test', function() {
    var_dump(App\Models\v0\block\transaction\output\address\Address::find(122371)->inputs);
});