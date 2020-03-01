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

Route::get('/', 'HomepageController@index');
//Route::get('/', function () {
//     return view('home');
// });

Route::get('/homepage', 'HomepageController@index');



Route::get("page/{any}", "Cms@viewPage")->name('cms.view')->where("any", ".*");

Route::get('home', 'HomePageController@index')->name('home');
// Route::get('/', 'Auth\RegisterController@register_global')->name('register_global');
// Route::get('/', 'Auth\RegisterController@register_students')->name('register_students');

// custom auth routes
Route::get('global_register', 'Auth\RegisterController@showGlobalRegisterForm')->name('global_register');
Route::get('register_students', 'Auth\RegisterController@showStudentRegisterForm')->name('register_students');
Route::get('register_company', 'Auth\RegisterController@showCompanyRegisterForm')->name('register_company');

Auth::routes();


Route::middleware('role:admin')->group(function () {
    Route::prefix("admin")->group(function() {
      Route::prefix("user")->group(function() {
        Route::get("/", "UserController@index")->name("user.index");
      });
      Route::prefix('cms')->group(function() {
        Route::get('/', "Cms@index")->name("cms.index");
        Route::get('create', "Cms@createPage")->name("cms.create");
        Route::get('edit/{id}', "Cms@editPage")->name("cms.edit");
        Route::get('delete/{id}', "Cms@delete")->name('cms.delete');

        Route::post("edit/{id}", 'Cms@edit')->name('cms.edit.post');
        Route::post('create', "Cms@create")->name("cms.create.post");
      });
    });
});
