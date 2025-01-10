<?php 
use Illuminate\Support\Facades\Route; 
use Illuminate\Support\Facades\Auth;

/* ------------------ Common Routes START ---------------------------- */
Auth::routes([ 'verify' => true ]);

 
Route::prefix('/admin')->name('admin.')->namespace('Admin')->group(function(){  
    Route::get('/permission_denied', 'DashboardController@permission_denied');
    /*** Admin Auth Route(s)***/
    Route::namespace('Auth')->group(function(){
        //Login Routes
        Route::get('/','LoginController@showLoginForm')->name('login');
        Route::get('/login','LoginController@showLoginForm')->name('login');
        Route::post('/login','LoginController@login')->name('login');
        Route::post('/logout','LoginController@logout')->name('logout');
        
        //Forgot Password Routes
        Route::get('/password/reset','ForgotPasswordController@showLinkRequestForm')->name('password.request');
        Route::post('/password/mobile','ForgotPasswordController@sendResetLinkEmail')->name('password.mobile');
        
        //Reset Password Routes
        Route::get('/password/reset/{token}','ResetPasswordController@showResetForm')->name('password.reset');
        Route::post('/password/reset','ResetPasswordController@reset')->name('password.update');
    });

    Route::middleware(['auth:admin', 'permission', 'authCheck'])->group(function () {
        //Put all of your admin routes here...
        Route::get('/dashboard','DashboardController@index')->name('dashboard')->middleware('isAllow:101,can_view');

        /// Profile & change password
        Route::get('/change-password', 'ProfileController@changePassword');
        Route::post('/change-password', 'ProfileController@updatePassword');

        Route::get('/profile', 'ProfileController@profile');
        Route::post('/profile', 'ProfileController@updateprofile');

        /// General Settings Routes
        Route::get('/general_settings','GeneralSettingsController@index')->name('general_settings');
        Route::post('/general_settings/update','GeneralSettingsController@update')->name('general_settings.update');
        
        /// Role Routes
        Route::resource('/role', 'RolesController');
        Route::post('/role/status','RolesController@change_status')->name('role.status');

        /// Role-Permissions Routes
        Route::resource('/role_permission', 'RolePermissionController');
        Route::post('/role_permission/status', 'RolePermissionController@update_role_permission')->name('role_permission.status');

        /// User-Permissions Routes
        Route::resource('/user_permission', 'UserPermissionController');
        Route::post('/user_permission/status', 'UserPermissionController@update_user_permission')->name('user_permission.status');

        /// Banner Routes
        Route::resource('/banner', 'BannerController');
        Route::post('/banner/status','BannerController@change_status')->name('banner.status'); 
        
        /// Banner Routes
        Route::resource('/offer', 'OfferController');
        Route::post('/offer/status','OfferController@change_status')->name('offer.status'); 

        /// Subadmin Routes
        Route::resource('/subadmin', 'SubadminController');
        Route::post('/subadmin/status','SubadminController@change_status')->name('subadmin.status');

        /// Cms Routes
        Route::resource('/cms', 'CmsController');
        Route::post('/cms/status','CmsController@change_status')->name('cms.status');

        /// Home Cms Routes
        Route::resource('/homecms', 'HomeCmsController');
        Route::post('/homecms/status','HomeCmsController@change_status')->name('homecms.status');

        /// Country Routes
        Route::resource('/countries', 'CountryController');
        Route::post('/countries/status','CountryController@change_status')->name('countries.status');

        /// State Routes
        Route::resource('/states', 'StateController');
        Route::post('/states/status','StateController@change_status')->name('states.status');
        Route::post('/getstate','StateController@get_state')->name('states.ajax');

         /// State Routes
        Route::resource('/cities', 'CityController');
        Route::post('/cities/status','CityController@change_status')->name('cities.status');
        Route::post('/getcity','CityController@get_city')->name('cities.ajax');

         /// Blog Category Routes
         Route::resource('/faq_types', 'FaqTypeController');
         Route::post('/faq_types/status', 'FaqTypeController@change_status')->name('faq_types.status');

        /// Faq Routes
        Route::resource('/faqs', 'FaqController');
        Route::post('/faqs/status','FaqController@change_status')->name('faqs.status');

        /// Testimonial Routes
        Route::resource('/testimonials', 'TestimonialController');
        Route::post('/testimonials/status','TestimonialController@change_status')->name('testimonials.status');

        /// Blog Category Routes
        Route::resource('/blog_categories', 'BlogCategoryController');
        Route::post('/blog_categories/status', 'BlogCategoryController@change_status')->name('blog_categories.status');

        // Blog Routes
        Route::resource('/blog', 'BlogController');
        Route::post('/blog/status', 'BlogController@change_status')->name('blog.status');

        ///Category Routes
        Route::resource('/categories', 'CategoryController');
        Route::post('/categories/status', 'CategoryController@change_status')->name('CategoryController.status');

        ///Product Routes
        Route::resource('/products', 'ProductController');
        Route::post('/products/status', 'ProductController@change_status')->name('products.status');
        Route::post('/products/sub_categories', 'ProductController@get_sub_categories')->name('products.sub_categories');

        /// Badge Master Routes
        Route::resource('/badge_masters', 'BadgeMasterController');
        Route::post('/badge_masters/status', 'BadgeMasterController@change_status')->name('badge_masters.status');
        
        /// Reward Master Routes
        Route::resource('/reward_masters', 'RewardMasterController');
        Route::post('/reward_masters/status', 'RewardMasterController@change_status')->name('reward_masters.status');

        /// Customer Address Routes
        Route::resource('/customer/{user_id}/address', 'UserAddressController');
        Route::post('/customer/{user_id}/address/status','UserAddressController@change_status')->name('address.status'); 

        /// Customer Routes
        Route::resource('/customer', 'UserController');
        Route::post('/customer/status','UserController@change_status')->name('customer.status'); 
        Route::get('/customer/{user_id}/details','UserController@details')->name('customer.details'); 
        Route::get('/customer/{user_id}/user_badges','UserController@user_badges')->name('customer.user_badges'); 
        Route::get('/customer/{user_id}/user_refer','UserController@user_refer')->name('customer.user_refer'); 
        Route::get('/customer/{user_id}/user_orders','UserController@user_orders')->name('customer.user_orders'); 
        Route::get('/customer/{user_id}/user_wallet','UserController@user_wallet')->name('customer.user_wallet'); 
        Route::get('/customer/{user_id}/user_withdraw','UserController@user_withdraw')->name('customer.user_withdraw'); 
        Route::get('/customer/withdraw_reject/{user_id}','UserController@withdraw_reject')->name('customer.withdraw_reject'); 
        Route::get('/customer/withdraw_approve/{user_id}','UserController@withdraw_approve')->name('customer.withdraw_approve'); 
        Route::get('/customer/{user_id}/user_address','UserController@user_address')->name('customer.user_address'); 
        Route::get('/customer/{user_id}/user_rewards','UserController@user_rewards')->name('customer.user_rewards'); 
        Route::get('/customer/{user_id}/user_profit_sharing','UserController@user_profit_sharing')->name('customer.user_profit_sharing'); 

        /// Contact Inquires Routes
        Route::resource('/contact_inquires', 'ContactInquiryController');

        /// Profit Sharing Routes
        Route::resource('/profit_shares', 'ProfitShareController');
        Route::post('/profit_shares/users','ProfitShareController@get_users')->name('profit_shares.users');

        /// Admin Notification Routes
        Route::resource('/admin_notifications', 'AdminNotificationController');
        
        // Orders Route
        Route::resource('/orders', 'OrderController');
        Route::post('/orders/status/{id}', 'OrderController@updOrderStatus')->name('orders.status');
        Route::get('/orders/invoice/{id}', 'OrderController@invoice')->name('orders.invoice');

        Route::get('/returns', 'ReturnController@index');
        Route::get('/returns/{id}', 'ReturnController@show');
        Route::post('/returns/status/{id}', 'ReturnController@updReturnStatus')->name('returns.status');

        /// Rewards Routes
        Route::resource('/rewards', 'RewardController');
        Route::get('/rewards/reject/{id}','RewardController@reject')->name('rewards.reject'); 
        Route::get('/rewards/approve/{id}','RewardController@approve')->name('rewards.approve');
    });
});
