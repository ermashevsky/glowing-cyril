<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/


Route::post('/sendQueryData', 'HomeController@sendQueryData');
Route::post('/deleteRule', 'HomeController@deleteRule');
Route::post('/getRuleParameter', 'HomeController@getRuleParameter');
Route::post('/saveRule', 'HomeController@saveRule');
Route::post('/getStatisticInfo', 'HomeController@getStatisticInfo');
Route::post('/updateRule', 'HomeController@updateRule');
Route::post('/findDireactionsData', 'HomeController@findDireactionsData');
Route::post('/getOperatorsList', 'HomeController@getOperatorsList');
Route::post('/addNewTariffs', 'HomeController@addNewTariffs');
Route::post('/addPrices', 'HomeController@addPrices');
Route::post('/fileUpload', 'HomeController@fileUpload');
Route::post('/comparePrice', 'HomeController@comparePrice');
Route::post('deleteTariff', array('as' => 'deleteTariff', 'uses' => 'HomeController@deleteTariff'));

Route::get('/', "HomeController@index");
Route::get('/operators', 'HomeController@statisticsOnOperators');
Route::get('/directions', 'HomeController@directions');
Route::get('/compareTariffs', 'HomeController@compareTariffs');
Route::get('getPrices/{id}', array('as' => 'getPrices', 'uses' => 'HomeController@getPricesList'));
Route::get('editPrices/{id}', array('as' => 'editPrices', 'uses' => 'HomeController@editPrices'));

Route::get('/viewComparedPrices', 'HomeController@viewComparedPrices');



