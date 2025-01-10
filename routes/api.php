<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// ................Artisan ................
Route::get('/clear-cache', function () {
    Artisan::call('cache:clear');
    return '<h1>Cache facade value cleared</h1>';
});

Route::get('/config-clear', function () {
    Artisan::call('config:clear');
    return '<h1>Cache facade value cleared</h1>';
});

Route::get('/clear-all', function () {
    Artisan::call('cache:clear');
    Artisan::call('route:clear');
    Artisan::call('view:clear');
    Artisan::call('storage:link');
    return '<h1>Clear All</h1>';
});

Route::get('/schedule-run', function() {
    $exitCode = Artisan::call('schedule:run');
    return '<h1>schedule-run</h1>';
}); 


/*Route::get('/paystatus', function () {
    Artisan::call('purchase:payment_status');   
    return '<h1>update paystatus</h1>';
});

Route::get('/badgereward', function () {
    Artisan::call('update:badgereward');   
    return '<h1>update badgereward</h1>';
});

Route::get('/walletpayment', function () {
    Artisan::call('update:walletpayment');   
    return '<h1>update walletpayment</h1>';
});*/