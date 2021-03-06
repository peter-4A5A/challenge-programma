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

Route::get("page/{any}", "CmsController@viewPage")->name('cms.view')->where("any", ".*");

Auth::routes();

Route::prefix("reviews")->group(function() {
    Route::get('/', 'ReviewController@index')->name('reviews.index');
    Route::post('/', 'ReviewController@index');
});



Route::namespace('Auth')->group(function() {
  Route::prefix('register')->group(function() {
    Route::get('student', 'RegisterController@createStudentPage')->name('register.student');
    Route::post('student', 'RegisterController@createStudent')->name('register.student.post');
    Route::get('company', 'RegisterController@createCompanyPage')->name('register.company');
    Route::post('company', 'RegisterController@createCompany')->name('register.company.post');
  });
});

Route::middleware('role:admin|student|company')->group(function() {
  Route::prefix('event')->group(function() {
    Route::post('add-ajax', 'EventController@ajaxCreate')->name('event.create.ajax.post');
  });
});

Route::middleware('role:admin|content-writer')->group(function() {
  Route::get('/management', "UserController@management")->name('management.index');
  Route::prefix('cms')->group(function() {
    Route::get('/', "CmsController@index")->name("cms.index");
    Route::get('create', "CmsController@createPage")->name("cms.create");
    Route::get('edit/{id}', "CmsController@editPage")->name("cms.edit");
    Route::get('delete/{id}', "CmsController@delete")->name('cms.delete');

    Route::post("edit/{id}", 'CmsController@edit')->name('cms.edit.post');
    Route::post('create', "CmsController@create")->name("cms.create.post");
  });
  Route::prefix('menu')->group(function() {
    Route::get('/', 'MenuController@index')->name('menu.index');
    Route::post('/addcustommenu', '\App\Override\Harimayco\Menu\Controllers\MenuController@addcustommenu')->name('haddcustommenu');
    Route::post('/deleteitemmenu', '\App\Override\Harimayco\Menu\Controllers\MenuController@deleteitemmenu')->name('hdeleteitemmenu');
    Route::post('/generatemenucontrol', '\App\Override\Harimayco\Menu\Controllers\MenuController@generatemenucontrol')->name('hgeneratemenucontrol');
    Route::post('/updateitem', '\App\Override\Harimayco\Menu\Controllers\MenuController@updateitem')->name('hupdateitem');
  });
  Route::prefix('photoalbum')->group(function() {
    Route::get('overview', 'PhotoalbumController@overview')->name('photoalbum.overview');
    Route::get('create', 'PhotoalbumController@createPhotoalbumPage')->name('photoalbum.create');
    Route::get('edit/{id}', 'PhotoalbumController@editPage')->name('photoalbum.edit');

    Route::post('edit/{id}', 'PhotoalbumController@storePhoto')->name('photoalbum.store.photo');
    Route::post('edit/album/{id}', 'PhotoalbumController@editAlbum')->name('photoalbum.edit.album');
    Route::get('delete/album/{oPhotoalbum}', 'PhotoalbumController@deleteAlbum')->name('photoalbum.delete');
    Route::get('delete/{id}', "PhotoalbumController@deletePhoto")->name('photoalbum.delete.photo');

    Route::post('create', 'PhotoalbumController@create')->name('photoalbum.create.post');
    Route::get('publish/{id}/prepare', 'PhotoalbumController@publishPrepare')->name('photoalbum.publish.prepare');
    Route::get('publish/execute', 'PhotoalbumController@publish')->name('photoalbum.publish.execute');

    Route::get('photo/{oImage}', 'PhotoalbumController@editPhotoPage')->name('photoalbum.photo.create');
    Route::post('photo/{oImage}', 'PhotoalbumController@editPhoto')->name('photoalbum.photo.create.post');
  });
});

Route::middleware('role:student')->group(function() {

  Route::prefix('student')->group(function() {
    Route::get("event/details/{id}", "EventController@details")->name("event.details");
      Route::prefix('profile')->group(function() {
          Route::get('/', "ProfileController@index")->name('profile.index');
          Route::post('/', "ProfileController@index");
          Route::get('terminate', 'ProfileController@terminatePage')->name('profile.terminate');
          Route::post('terminate', 'ProfileController@terminate')->name('profile.terminate.post');
      });
    Route::prefix('event')->group(function() {
      Route::get("details/{id}", "EventController@details")->name("event.details.student");
      Route::get('register/{id}', "EventController@studentRegisterPage")->name('event.register.student');
      Route::post('register/{id}', "EventController@studentRegister")->name('event.register.student.post');
    });
  });
});

Route::middleware('role:company')->group(function() {
  Route::prefix('bedrijf')->group(function() {
    Route::get('review/add', 'ReviewController@addReviewPage')->name('review.add');
    Route::post('review/add', 'ReviewController@addReview')->name('review.add.post');
  });
});

Route::middleware('role:admin')->group(function () {
  Route::prefix('admin')->group(function() {
    Route::prefix("event")->group(function() {
      Route::get('/', 'EventController@index')->name('event.index');
      Route::get('create', 'EventController@createPage')->name('event.create');
      Route::post('create', 'EventController@create')->name('event.create.post');
      Route::get('edit/{id}', 'EventController@editPage')->name('event.edit');
      Route::post('edit/{id}', 'EventController@edit')->name('event.edit.post');
      Route::get("delete/{id}", "EventController@delete")->name("event.delete");
      Route::get('present/{id}', "EventController@presentPage")->name('event.present');
      Route::post('present/{id}', "EventController@present")->name('event.present.page');
      Route::get("details/{id}", "EventController@details")->name("event.details");
      Route::get("accept/{id}", "EventController@accept")->name("event.accept");
    });
    Route::prefix("user")->group(function() {
      Route::get("/", "UserController@index")->name("user.index");
      Route::get('edit/{id}', 'UserController@editPage')->name('user.edit');
      Route::get('delete/{id}', 'UserController@deletePage')->name('user.deleteExistingPage');
      Route::get('delete-existing/{id}', 'UserController@deleteExisting')->name('user.deleteExisting');
      Route::post('update/student/{id}', 'UserController@updateStudent')->name('user.update.student.post');
      Route::post('update/company/{id}', 'UserController@updateCompany')->name('user.update.company.post');
      Route::post('update/admin/{id}', 'UserController@updateAdmin')->name('user.update.admin.post');
      Route::get('accept-users', 'UserController@notAcceptedUserOverview')->name('user.not.accepted.overview');
      Route::get('delete-user/{id}', 'UserController@deleteUser')->name('user.delete');
      Route::get('accept-user/{id}', 'UserController@acceptUser')->name('user.accept');
      Route::get('details/{id}', 'UserController@details')->name('user.details');
      Route::get('create-user', 'UserController@createPage')->name('user.create');
      Route::post('create-user', 'UserController@createUser')->name('user.create.post');
    });
    Route::prefix('image')->group(function() {
      Route::get('/', 'ImageController@index')->name('image.index');
      Route::post('/store', 'ImageController@store')->name('image.store.post');
      Route::get('delete/{id}', 'ImageController@delete')->name('image.delete');
    });

    Route::prefix('mail')->group(function() {
      Route::get('/create', 'MailController@createPage')->name('mail.create');
      Route::post('/create', 'MailController@create')->name('mail.create.post');
    });
    Route::prefix('review')->group(function() {
      Route::get('/', 'ReviewController@overview')->name('review.overview');
      Route::get('delete-review/{id}', 'ReviewController@deletePage')->name('review.delete');
      Route::post('delete-review/{id}', 'ReviewController@deleteReview')->name('review.delete.post');
    });
  });
});

Route::get('search', 'SearchController@index')->name('search.index');

Route::get('/agenda', 'EventController@agenda')->name('event.agenda');
Route::get('/agenda/detail/{id}', 'EventController@agendaDetails')->name('event.details.api');

Route::get('photos/{id}', 'PhotoalbumController@photoCollection')->name('photoalbum.photos');

Route::prefix('photoalbum')->group(function () {
  Route::get('/', 'PhotoalbumController@index')->name('photoalbum.index');
  Route::post('/', 'PhotoalbumController@index');
});

Route::get("event/details/{id}", "EventController@detailsGuest")->name("event.details.guest");
