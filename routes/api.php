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


// New One Crones 
Route::get('/ledgerstatus', function () {
    Artisan::call('update:ledgerstatus');   
    return '<h1>update ledgerstatus</h1>';
});
Route::get('/referandcommissionledgerstatus', function () {
    Artisan::call('update:referandcommissionledgerstatus');   
    return '<h1>update referandcommissionledgerstatus</h1>';
});
Route::get('/membershipvalidity', function () {
    Artisan::call('update:membershipvalidity');   
    return '<h1>update membershipvalidity</h1>';
});
