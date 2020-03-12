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

Route::get('home', 'HomepageController@index')->name('home');

Route::get('homepage', 'HomepageController@index');

Route::get("page/{any}", "Cms@viewPage")->name('cms.view')->where("any", ".*");

Route::get('reviews', "ReviewController@index");

Auth::routes();

Route::namespace('Auth')->group(function() {
  Route::prefix('register')->group(function() {
    Route::get('student', 'RegisterController@createStudentPage')->name('register.student');
    Route::post('student', 'RegisterController@createStudent')->name('register.student.post');
    // Route::get('company', 'CompanyRegisterController@index')->name('register.company');
    // Route::post('company', 'CompanyRegisterController@create')->name('register.company.post');
  });
});

Route::middleware('role:admin')->group(function () {
  Route::prefix('admin')->group(function() {
    Route::get('/', function(){
      return view('management.index');
    });
    Route::prefix("event")->group(function() {
      Route::get('/', 'EventController@index')->name('event.index');
      Route::get('create', 'EventController@createPage')->name('event.create');
      Route::post('create', 'EventController@create')->name('event.create.post');
    });
    Route::prefix("user")->group(function() {
      Route::get("/", "UserController@index")->name("user.index");
      Route::get("update/{id}", "UserController@editPage")->name("user.edit");
      Route::post('update/{id}', 'UserController@edit')->name('user.edit.post');
    });
    Route::prefix('image')->group(function() {
      Route::get('/', 'ImageController@index')->name('image.index');
      Route::post('/store', 'ImageController@store')->name('image.store.post');
    });
    Route::prefix('cms')->group(function() {
      Route::get('/', "Cms@index")->name("cms.index");
      Route::get('create', "Cms@createPage")->name("cms.create");
      Route::get('edit/{id}', "Cms@editPage")->name("cms.edit");
      Route::get('delete/{id}', "Cms@delete")->name('cms.delete');

      Route::post("edit/{id}", 'Cms@edit')->name('cms.edit.post');
      Route::post('create', "Cms@create")->name("cms.create.post");
      });
      Route::prefix("new-student")->group(function() {
        Route::get("/", "NewStudentController@index")->name("newstudent.index");
        Route::get("delete/{id}", "NewStudentController@delete")->name("newstudent.delete");
        Route::get("accept/{id}", "NewStudentController@accept")->name("newstudent.accept");
      });
    });
});

Route::get('/agenda', 'EventController@agenda')->name('event.agenda');
Route::get('/agenda/detail/{id}', 'EventController@agendaDetails')->name('event.details.api');
