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

// LIST ARTICLES
Route::get("articles", "ArticlesController@index");

// SHOW SINGLE ARTICLE
Route::get('article/{id}',"ArticlesController@show");

// Create An ARTICLE
Route::post("article", "ArticlesController@store");

// UPDATE ARTICLE
Route::put('article',"ArticlesController@store");

// DELETE ARTICLES
Route::delete("article/{id}", "ArticlesController@destroy");

