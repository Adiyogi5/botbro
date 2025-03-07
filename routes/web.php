<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;


/* ------------------ Common Routes START ---------------------------- */
Auth::routes([ 'verify' => true ]);

Route::get('/', 'Frontend/HomeController@index')->name('/');

 
Route::prefix('/')->name('frontend.')->namespace('Frontend')->group(function () {

    Route::get('/', 'HomeController@index')->name('home');
    
    Route::get('/joinus', 'JoinUsController@index')->name('joinus');
    Route::post('/register-user', 'JoinUsController@register_user')->name('register_user');
    Route::get('/send-otp', 'JoinUsController@sendOTP')->name('send-otp');
    Route::post('/check-referral-code', 'JoinUsController@checkReferralCode')->name('checkReferralCode');

    Route::get('/why-join-robotrade', 'WhyUpayController@index')->name('whyrobotrade');
    ///Route::get('/faqs', 'FaqController@index')->name('faqs');

    Route::get('/about-us', 'AboutUsController@index')->name('aboutus');
    Route::get('/our-leadership', 'OurLeadershipController@index')->name('ourleadership');

    Route::get('/blog/{slug?}', 'BlogController@index')->name('blog');
    Route::get('/blog-details/{slug}', 'BlogController@blogDetails')->name('blogdetails');

    Route::get('/contact-us', 'ContactUsController@index')->name('contactus');
    Route::get('/reload-captcha', 'ContactUsController@reloadCaptcha')->name('reload_captcha');
    Route::post('/submit-contact', 'ContactUsController@submitcontact')->name('submitcontact');

    Route::get('/privacy-policy', 'PrivacyController@index')->name('privacy');
    Route::get('/terms-conditions', 'TermsController@index')->name('terms');
    // Route::get('/cancel-refund-policy', 'ReturnController@index')->name('return');
    Route::get('/cancel-refund-policy', 'ShipCancelController@index')->name('shipandcancel');

    Route::namespace('Auth')->group(function(){
        //Login Routes
        Route::get('/login','LoginController@showLoginForm')->name('login');
        Route::get('/logout','LoginController@showLoginForm')->name('logout');
        Route::post('login','LoginController@login')->name('login');
        Route::post('logout','LoginController@logout')->name('logout');
        
        //Forgot Password Routes
        Route::get('/password/reset','ForgotPasswordController@showLinkRequestForm')->name('password.request');
        Route::post('/password/mobile','ForgotPasswordController@sendResetLinkEmail')->name('password.mobile');

        //Reset Password Routes
        route::get('/password/reset/{mobile}','ResetPasswordController@showresetform')->name('password.reset');
        route::post('/password/reset','ResetPasswordController@reset')->name('password.update');

    });

    Route::middleware(['auth:web', 'authCheck'])->group(function () {
        Route::get('/dashboard', 'Dashboard\DashboardController@index')->name('dashboard');
        Route::post('/qrcode-payment', 'Dashboard\DashboardController@qrcodepayment')->name('qrcodepayment');

        Route::get('/products/{slug?}', 'Dashboard\ProductController@index')->name('products');
        Route::get('/product-details/{slug}', 'Dashboard\ProductController@productdetail')->name('productdetails');

        #checkout 
        Route::get('/cart','CartController@index')->name('cart');
        Route::post('/add-to-cart','CartController@addtocart')->name('add-to-cart');
        Route::get('/remove-from-cart','CartController@removecart')->name('remove-from-cart');
        Route::get('/update-cart','CartController@updatecartitem')->name('update-cart');
        Route::get('/get-cart-count','CartController@getCartCount')->name('get-cart-count');
        Route::get('/checkout', 'CartController@checkout')->name('checkout');
        Route::post('/placeorder', 'CartController@placeorder')->name('placeorder');
        Route::get('/checkout-verify', 'CartController@checkoutVerify')->name('checkout_verify');

        Route::post('/verify-payment', 'CartController@verify_payment')->name('verify-payment');
        Route::get('/payment', 'CartController@pay')->name('pay');
        Route::get('/paymentconfirm', 'CartController@paymentconfirm')->name('paymentconfirm');

        Route::get('/my-order', 'Dashboard\MyOrderController@index')->name('my_order');
        Route::get('/get_filter_data', 'Dashboard\MyOrderController@get_filter_data')->name('get_filter_data');
        Route::get('/order-view/{id}', 'Dashboard\OrderViewController@index')->name('order_view');
        Route::get('/my-return', 'Dashboard\MyOrderController@my_return')->name('my_return');
        Route::get('/get_filter_return_data', 'Dashboard\MyOrderController@get_filter_return_data')->name('get_filter_return_data');
        Route::post('/return-product', 'Dashboard\OrderViewController@return_product')->name('return-product');

        Route::get('/my-wallet', 'Dashboard\MyWalletController@index')->name('my_wallet');

        Route::get('/my-address', 'Dashboard\MyAddressController@index')->name('my_address');
        Route::get('/my-address/add', 'Dashboard\MyAddressController@add')->name('my_address.add');
        Route::post('/my-address', 'Dashboard\MyAddressController@save')->name('my_address');
        Route::put('/my-address', 'Dashboard\MyAddressController@update')->name('my_address');
        Route::delete('/my-address/deleteaddress', 'Dashboard\MyAddressController@delete')->name('my_address.deleteaddress');
        Route::get('/switch_address', 'Dashboard\MyAddressController@switch_address')->name('switch_address');

        Route::post('/getstate','Dashboard\MyAddressController@get_state')->name('states.ajax');
        Route::post('/getcity','Dashboard\MyAddressController@get_city')->name('cities.ajax');

        Route::get('/profile', 'Dashboard\ProfileController@index')->name('profile');
        Route::put('/profile', 'Dashboard\ProfileController@update')->name('profile');
        Route::get('/confirm-password', 'Dashboard\ConfirmPassController@index')->name('confirm_password');
        Route::post('/confirm-password/change-password', 'Dashboard\ConfirmPassController@changePassword')->name('confirm_password.change-password');

        Route::get('/badge-history', 'Dashboard\BadgeController@index')->name('badge_history');
        Route::get('/reward-history', 'Dashboard\RewardController@index')->name('reward_history');
        Route::get('/profit-history', 'Dashboard\ProfitController@index')->name('profit_history');
        Route::get('/withdrow-request', 'Dashboard\WithdrowController@index')->name('withdrow_request');
       
        Route::post('/withdrow', 'Dashboard\WithdrowController@withdrow')->name('withdrow');

        Route::get('/investment', 'Dashboard\MyInvestmentController@index')->name('investment');
        Route::post('/invest-money', 'Dashboard\MyInvestmentController@investmoney')->name('investmoney');
        Route::get('/get_filter_data', 'Dashboard\MyInvestmentController@get_filter_data')->name('get_filter_data');
        Route::get('/investment-details/{id}', 'Dashboard\MyInvestmentController@investmentDetails')->name('investmentdetails');
        
        Route::post('/fullwithdrow-investment/{id}', 'Dashboard\MyInvestmentController@fullwithdrowInvestment')->name('fullwithdrowinvestment');    
        Route::post('/check-ledger-month', 'Dashboard\MyInvestmentController@checkLedgerMonth')->name('checkLedgerMonth');

        Route::post('/withdrow-investment/{id}', 'Dashboard\MyInvestmentController@withdrowInvestment')->name('withdrowinvestment');
        
        Route::get('/reffer-history', 'Dashboard\RefferController@index')->name('reffer_history');
        Route::post('/withdrow-reffer-request', 'Dashboard\RefferController@withdrowrefferrequest')->name('withdrow_reffer_request');

    }); 

});
 
        
    