<?php
/*Route::get('/route-clear', function() {
    Artisan::call('route:clear');
    return "Cache is cleared";
});
Route::get('/clear-cache', function() {
    Artisan::call('cache:clear');
    return "Cache is cleared";
});
Route::get('/config-clear', function() {
    Artisan::call('config:clear');
    return "Cache is cleared";
});
Route::get('/foo', function(){
	Artisan::call('storage:link');
});*/

/* --------------------- Common/User Routes START -------------------------------- */
Route::get('/register', 'Auth\RegisterController@showRegistrationForm');
Route::get('/', 'WelcomeController@landing_index');
Route::get('/study_program', 'WelcomeController@study_program');
Route::get('/videoplaylist', 'WelcomeController@videoPlaylist');

Route::get('/categorylist', 'WelcomeController@catlist');
Route::get('/ajaxcategorylist', 'WelcomeController@ajaxcategorylist');
Route::get('/blog', 'WelcomeController@blog');



Auth::routes([ 'verify' => true]);

Route::get('/home', 'HomeController@index')->name('home')->middleware('verified');
Route::get('logout', 'LoginController@logout')->name('logout');
Route::group(['middleware' => ['auth'], 'namespace' => 'User'], function() {
    // setting route
    Route::get('/edit_profile', 'UserController@edit_profile')->name('edit_profile');
    Route::post('/edit_profile', 'UserController@update_profile');
    //change Password
    Route::get('/change_password', 'UserController@change_pwd');
    Route::post('/change_password', 'UserController@update_changed_pwd');

    
    //seller Rating
    Route::match(['get'], 'rate_your_seller', 'UserController@rate_your_seller');
    Route::post('/post_rating', 'UserController@post_rating');
	
    //Message Routing
    Route::get('/message/{id}', 'UserController@messaging');
    Route::post('/message/{id}', 'UserController@post_message');
    Route::get('/my_buyer_list', 'UserController@my_buyer_list');
    
    //Blog comment
    Route::post('/blog/comment/{id}', 'BlogController@post_blog_comment');
    
    //pod comment
    Route::post('/pod/comment/{id}', 'BlogController@post_pod_comment');
    
    // My Account
    Route::get('/my_account', 'UserController@edit_account_info')->name('my_account');
    Route::post('/my_account', 'UserController@update_my_account');
});
/* --------------------- Common/User Routes END -------------------------------- */

/* ----------------------- Admin Routes START -------------------------------- */

Route::prefix('/admin')->name('admin.')->namespace('Admin')->group(function() {

    /**
     * Admin Auth Route(s)
     */
    Route::namespace('Auth')->group(function() {
		
        //Login Routes
        Route::get('/login', 'LoginController@showLoginForm')->name('login');
        Route::post('/login', 'LoginController@login');
        Route::post('/logout', 'LoginController@logout')->name('logout');

        Route::get('/password/reset', 'ForgotPasswordController@showLinkRequestForm')->name('password.request');
        Route::post('/password/email', 'ForgotPasswordController@sendResetLinkEmail')->name('password.email');

        //Reset Password Routes
        Route::get('/password/reset/{token}', 'ResetPasswordController@showResetForm')->name('password.reset');
        Route::post('/password/reset', 'ResetPasswordController@reset')->name('password.update');

        // Email Verification Route(s)
        Route::get('email/verify', 'VerificationController@show')->name('verification.notice');
        Route::get('email/verify/{id}', 'VerificationController@verify')->name('verification.verify');
        Route::get('email/resend', 'VerificationController@resend')->name('verification.resend');
    });
	Route::get('/', 'HomeController@index');
	
    //settings route
    Route::get('/settings', 'AdminController@settings');
    Route::post('/settings', 'AdminController@update_settings');
	
    //change password route
    Route::get('/change_password', 'AdminController@change_pwd');
    Route::post('/change_password', 'AdminController@update_changed_pwd');
	
    //user's Routes
    Route::get('/user_list', 'UserController@user_list');
    Route::get('/fetchUsers', 'UserController@fetchUsers');
    Route::get('/user/{id?}', 'UserController@create_add_user');
    Route::post('/add_user', 'UserController@store');
    Route::post('/edit_user/{id?}', 'UserController@store');
    Route::get('/user/delete/{id}', 'UserController@destroy');
	Route::get('/level_approvals', 'UserController@level_approvals');
    Route::get('/fetchUnapprovedLevelUser', 'UserController@fetchUnapprovedLevelUser');

    //category routes
    Route::get('/category_list', 'CategoryController@index');
    Route::get('/category/{id?}', 'CategoryController@create');
    Route::post('/add_category', 'CategoryController@store');
    Route::post('/edit_category/{id}', 'CategoryController@store');
    Route::get('/fetchcategories', 'CategoryController@fetchcategories');
    Route::get('/category/delete/{id}', 'CategoryController@destroy');
    Route::get('/feature_category/{id}', 'CategoryController@feature_category');
    
	
	//course
    Route::get('/course_list', 'CourseController@index');
    Route::get('/fetchCourses', 'CourseController@fetchCourses');
    Route::get('/course/{id?}', 'CourseController@create');
    Route::post('/add_course', 'CourseController@store');
    Route::post('/edit_counse/{id}', 'CourseController@store');
	Route::get('/course/delete/{id}', 'CourseController@destroy');
    
    //blog
    Route::get('/blog_list', 'BlogController@index');
    Route::get('/blog/{id?}', 'BlogController@create');
    Route::post('/add_blog', 'BlogController@add_blog');
    Route::post('/edit_blog/{id}', 'BlogController@add_blog');
    Route::get('/fetchblogs', 'BlogController@fetchblogs');
    Route::get('/blog/delete/{id}', 'BlogController@destroy');
    Route::get('/gallary', 'BlogController@gallary');
    Route::get('/upload_image', 'BlogController@upload_image');
    Route::post('/upload_image', 'BlogController@upload_image');
    Route::post('/upload_ck_image', 'BlogController@upload_ck_image')->name('upload');
    Route::get('/blog_approval', 'BlogController@comment_approval');
    Route::get('/fetchBlogUnapprovedCmt', 'BlogController@fetchBlogUnapprovedCmt');
    Route::get('/comment/delete/{id}', 'BlogController@destroyComment');
    Route::get('/blog/publish/{id}', 'BlogController@publishComment');
    
    Route::get('/dashboard', 'HomeController@index')->name('home')->middleware('guard.verified:admin,admin.verification.notice');


    //Put all of your admin routes here...
});

/* ----------------------- Admin Routes END -------------------------------- */
