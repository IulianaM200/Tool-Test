<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//FIRST ROUTE
//Route::get('/', function () {
//    return view('welcome');
//});

Route::get('/', function()
{
    return View::make('pages.home');
});
Route::get('about', function()
{
    return View::make('pages.about');
});
Route::get('projects', function()
{
    return View::make('pages.projects');
});
Route::get('contact', function()
{
    return View::make('pages.contact');
});

Route::get('tables',  array('as' => 'tables', 'uses' => 'Database\TablesController@tables'));
Route::post('tablesColumnsAndData', array('as' => 'tables/columns/data', 'uses' => 'Database\TablesController@tablesColumnsAndData'));
Route::post('tablesAddTable', array('as' => 'tables/add/table', 'uses' => 'Database\TablesController@addTable'));
Route::post('tablesRemoveTable', array('as' => 'tables/remove/table', 'uses' => 'Database\TablesController@removeTable'));
Route::post('tablesInsert', array('as' => 'tables/insert', 'uses' => 'Database\TablesController@insert'));

//Route::post('/index/add', array('as' => 'add', 'uses' => 'IndexController@add'));
//Route::post('/index/remove', array('as' => 'remove', 'uses' => 'IndexController@remove'));
//Route::post('/index/selectTable', array('as' => 'selectTable', 'uses' => 'IndexController@selectTable'));
//Route::post('/index/selectTable/insert', array('as' => 'selectTable.insert', 'uses' => 'IndexController@selectTableInsert'));

