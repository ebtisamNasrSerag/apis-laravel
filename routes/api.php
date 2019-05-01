<?php

use Illuminate\Http\Request;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('v1')->group(function(){
 Route::post('login', 'Api\AuthController@login');
 Route::post('register', 'Api\AuthController@register');

 Route::middleware('auth:api')->group(function () {
    Route::get('user', 'Api\AuthController@details');

    Route::resource('products', 'Api\ProductController');
    Route::post('favorite', 'Api\HomeController@add_to_favorite');
    Route::get('favorite', 'Api\HomeController@user_favorite');

   
  });

 Route::get('all_products', 'Api\HomeController@index');
 Route::get('product_desc/{id}', 'Api\HomeController@show_desc');

});
