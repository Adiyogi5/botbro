<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CityController;
use App\Http\Controllers\FireController;
use App\Http\Controllers\Common\CommonController;
use App\Http\Controllers\FrontController;

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

// Route::patch('fcm-token', [FireController::class, 'updateToken'])->name('fcmToken');
Route::controller(FrontController::class)->name('front.')->group(function () {
    Route::get('/', 'index')->name('home');
    Route::get('/home', 'index')->name('home');

    Route::get('/contact-us', 'contactUs')->name('contact-us');
    Route::post('/contact-us', 'contactUsSave')->name('contact-us');

    Route::get('{cms}', 'showCms')->name('show-cms')->whereIn('cms', ['about-us', 'terms-condition', 'privacy-policy']);
    Route::get('/faqs', 'faqs')->name('faqs');
});


Route::get('/push-notificaiton', [FireController::class, 'index'])->name('push-notificaiton');
Route::post('/store-token', [FireController::class, 'storeToken'])->name('store.token');
Route::post('/send-web-notification', [FireController::class, 'sendWebNotification'])->name('send.web-notification');

Route::get('test', [CommonController::class, 'test'])->name('test');
Route::get('{guard}', fn ($guard) => redirect($guard == 'admin' ?  url('/admin/login') : url("/$guard/login")))->whereIn('guard', ['admin']);
Route::redirect('admin/dashboard', '/dashboard');


Route::middleware(['authCheck'])->group(function () {

    // Open Routes
    Route::post('get-cities', [CityController::class, 'get_cities'])->name('cities.list');
    Route::post('upload-image', [CommonController::class, 'upload_image'])->name('upload_image');
    Route::get('get-user-list-filter', [CommonController::class, 'get_user_list_filter'])->name('get_user_list_filter');
});

Route::fallback(function () {
    abort(404);
});
