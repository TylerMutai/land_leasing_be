<?php

use App\Http\ACL\Roles;
use Illuminate\Support\Facades\Route;

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

//Login
Route::post('login', 'Auth\LoginController@login');

Route::get('email/verify', 'Auth\VerificationController@index')->name('verification.notice');

Route::group(['middleware' => ['signed']], function () {
    Route::get('email/verify/{id}/{hash}', 'Auth\VerificationController@verify')->name('verification.verify');
});

//Email Verification
Route::group(['middleware' => ['auth:api']], function () {
    Route::group(['middleware' => ['throttle:6,1']], function () {
        Route::get('email/send', 'Auth\VerificationController@send')->name('verification.send');
    });
});

//API Routes - Routes that require their responses to be in form of json. (Appends 'Accept' header)
Route::group(['middleware' => ['api_json']], function () {

    //General routes
    Route::group(['middleware' => ['auth:api', 'verified']], function () {
        Route::get('me', 'UsersController@loggedInUser');

        Route::patch('me', 'UsersController@updateUser');
    });

    //General unauthenticated routes
    Route::get('lands', 'Users\LandsController@get');

    Route::get('lands/{id}', 'Users\LandsController@getDetail');

    Route::get('lands/image/{id}', 'Users\LandsController@getImage');

    Route::get('products', 'Users\ProductsController@get');

    Route::get('products/{id}', 'Users\ProductsController@getDetail');

    Route::get('products/image/{id}', 'Users\ProductsController@getImage');

    Route::get('location/search', 'Users\LocationsController@search');

    Route::get('blogs', 'Admin\BlogsController@get');

    Route::get('blogs/{id}', 'Admin\BlogsController@getDetail');




    //User routes
    Route::group(['prefix' => 'users'], function () {
        
        Route::post('register', 'Auth\RegistrationController@registerUser');
        
        Route::group(['middleware' => ['auth:api', 'verified']], function () {
            Route::group(['middleware' => ["role:" . Roles::$USER]], function () {

                Route::post('lands/buy', 'Users\LandsController@buy');

                Route::get('lands', 'Users\LandsController@getMyLands');

                Route::group(['prefix' => 'mpesa'], function () {

                    Route::post('validate', 'Mpesa\MpesaController@validateMpesaSTKPush');

                    Route::get('confirm?secret=rsWsX127qunXNYcw', 'Mpesa\MpesaController@confirm');

                    Route::get('validate?secret=rsWsX127qunXNYcw', 'Mpesa\MpesaController@validateMpesa');

                    Route::post('stk-push', 'Mpesa\MpesaController@stkPush');
                });

            });
        });
    });

    //Farmer Routes
    Route::group(['prefix' => 'farmers'], function () {
        Route::post('register', 'Auth\RegistrationController@registerFarmer');

        Route::group(['middleware' => ['auth:api', 'verified']], function () {
            Route::group(['middleware' => ["role:" . Roles::$LESSOR]], function () {

                //Lands
                Route::patch('lands', 'Farmers\LandsController@update');

                Route::get('lands', 'Farmers\LandsController@get');

                Route::post('lands', 'Farmers\LandsController@upload');

                Route::post('lands/{id}', 'Users\LandsController@getDetail');

                Route::post('lands/deactivate/{id}', 'Farmers\LandsController@deactivate');

                Route::post('lands/image/{id}', 'Farmers\LandsController@uploadImage');

                Route::delete('lands/{id}', 'Farmers\LandsController@delete');

            });
        });
    });
});

Route::group(['prefix' => 'merchants'], function () {

    Route::post('register', 'Auth\RegistrationController@registerMerchant');

    Route::group(['middleware' => ['auth:api']], function () {
        Route::group(['middleware' => ["role:" . Roles::$MERCHANT]], function () {

            //Products
            Route::patch('products', 'Merchants\ProductsController@update');

            Route::get('products', 'Merchants\ProductsController@get');

            Route::post('products', 'Merchants\ProductsController@upload');

            Route::post('products/{id}', 'Merchants\ProductsController@getDetail');

            Route::delete('products/{id}', 'Farmers\Merchants\ProductsController@delete');
        });
    });
});

Route::group(['prefix' => 'admin'], function () {

    Route::group(['middleware' => ['auth:api']], function () {
        Route::group(['middleware' => ["role:" . Roles::$ADMIN]], function () {

            Route::post('blogs', 'Admin\BlogsController@upload');

            Route::patch('blogs/{id}', 'Admin\BlogsController@update');

            Route::delete('blogs/{id}', 'Admin\BlogsController@delete');

            Route::get('blogs', 'Admin\BlogsController@get');

            Route::get('blogs/{id}', 'Admin\BlogsController@getDetail');

            Route::get('lands/bought', 'Admin\LandsController@getBought');

            Route::get('lands', 'Admin\LandsController@get');

            Route::get('products', 'Admin\ProductsController@get');

            Route::get('users', 'Admin\UsersController@get');

            Route::get('users/{id}', 'Admin\UsersController@delete');

        });
    });
});

Route::get('create-roles-and-permissions', 'ACL\ACLController@create');


//Return JSON response for 404 routes.
Route::fallback(function () {
    return response()->json(['message' => 'Page not found'], 404);
});
