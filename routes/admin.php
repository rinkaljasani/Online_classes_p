<?php

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['revalidate']], function () {
	Route::get('/home',function(){
		return redirect(route('admin.dashboard.index'));
	})->name('home');

    // Profile
		Route::get('profile/', 'Admin\PagesController@profile')->name('profile-view');
		Route::post('profile/update', 'Admin\PagesController@updateProfile')->name('profile.update');
	    Route::put('change/password', 'Admin\PagesController@updatePassword')->name('update-password');

	// Quick Link
	Route::get('quickLink', 'Admin\PagesController@quickLink')->name('quickLink');
	Route::post('link/update', 'Admin\PagesController@updateQuickLink')->name('update-quickLink');
});

Route::group(['namespace' => 'Admin', 'middleware' => ['check_permit','revalidate']], function () {

	/* Dashboard */
	Route::get('/','PagesController@dashboard')->name('dashboard.index');
	Route::get('/dashboard', 'PagesController@dashboard')->name('dashboard.index');

	/* User */
	Route::get('users/listing', 'UsersController@listing')->name('users.listing');
	Route::resource('users','UsersController');


	/* Role Management */
	Route::get('roles/listing', 'AdminController@listing')->name('roles.listing');
	Route::resource('roles','AdminController');

	/* Country Management*/
	Route::get('countries/listing', 'CountryController@listing')->name('countries.listing');
	Route::resource('countries', 'CountryController');

	/* State Management*/
	Route::get('states/listing', 'StateController@listing')->name('states.listing');
	Route::resource('states', 'StateController');

	/* City Management*/
	Route::get('cities/listing', 'CityController@listing')->name('cities.listing');
	Route::resource('cities', 'CityController');

	/* CMS Management*/
	Route::get('pages/listing', 'CmsPagesController@listing')->name('pages.listing');
	Route::resource('pages', 'CmsPagesController');

	/* Site Configuration */
	Route::get('settings', 'PagesController@showSetting')->name('settings.index');
	Route::post('change-setting', 'PagesController@changeSetting')->name('settings.change-setting');

    /* Project */
	Route::get('projects/listing', 'ProjectController@listing')->name('projects.listing');
	Route::resource('projects', 'ProjectController');

    /* PLan */
	Route::get('plans/listing', 'PlansController@listing')->name('plans.listing');
	Route::resource('plans', 'PlansController');

    /* User Plans  */
	Route::get('user_plans/listing', 'UserPlanController@listing')->name('user_plans.listing');
	Route::resource('user_plans', 'UserPlanController');

    /* PLan */
	Route::get('faqs/listing', 'FaqsController@listing')->name('faqs.listing');
	Route::resource('faqs', 'FAQsController');


});

//User Exception
Route::get('users-error-listing', 'Admin\ErrorController@listing')->name('error.listing');
//Chart routes
Route::get('register-users-chart', 'Admin\ChartController@getRegisterUser')->name('users.registerchart');
Route::get('active-deactive-users-chart', 'Admin\ChartController@getActiveDeactiveUser')->name('users.activeDeactiveChart');

Route::post('check-email', 'UtilityController@checkEmail')->name('check.email');
Route::post('check-contact', 'UtilityController@checkContact')->name('check.contact');

Route::post('summernote-image-upload','Admin\SummernoteController@imageUpload')->name('summernote.imageUpload');
Route::post('summernote-media-image','Admin\SummernoteController@mediaDelete')->name('summernote.mediaDelete');

Route::post('check-title', 'UtilityController@checkTitle')->name('check.title');
Route::post('profile/check-password', 'UtilityController@profilecheckpassword')->name('profile.check-password');

Route::post('/get-users-plans', 'UtilityController@getProjectsUsersAndPlan')->name('get_projects.users_plans');
Route::post('/get-active-users-devices', 'UtilityController@getActiveUserDevices')->name('get_active_users_devices');
