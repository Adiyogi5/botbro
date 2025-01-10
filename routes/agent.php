<?php 
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/* ------------------ Common Routes START ---------------------------- */
Auth::routes([ 'verify' => true ]);


Route::prefix('/agent')->name('agent.')->namespace('Agent')->group(function () {
    
    Route::namespace('Auth')->group(function(){
        //Login Routes
        Route::get('/login','LoginController@showLoginForm')->name('login');
        Route::get('/logout','LoginController@showLoginForm')->name('logout');
        Route::post('web/login','LoginController@login')->name('web/login');
        Route::post('web/logout','LoginController@logout')->name('web/logout');
        
        //Forgot Password Routes
        Route::get('/password/reset','ForgotPasswordController@showLinkRequestForm')->name('password.request');
        Route::post('/password/email','ForgotPasswordController@sendResetLinkEmail')->name('password.email');
        
        //Reset Password Routes
        Route::get('/password/reset/{token}','ResetPasswordController@showResetForm')->name('password.reset');
        Route::post('/password/reset','ResetPasswordController@reset')->name('password.update');
    });

    Route::middleware(['auth:agent', 'authCheck'])->group(function () {
        Route::get('/dashboard', [HomeController::class,'dashboard'])->name('dashboard');
    });

});
 