<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\FlutterwaveController;
use App\Http\Controllers\Auth\CustomLoginController;
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

// Route::get('/', function () {
//     return view('welcome');
// });


Route::get('/', [HomeController::class, 'login']);
// Route::get('/', [HomeController::class, 'index']);

Route::get('/home', [HomeController::class, 'redirect']);

Route::get('/donatepage', [HomeController::class, 'donatepage']);

Route::get('/paypage', [HomeController::class, 'paypage']);
Route::get('/about', [HomeController::class, 'about']);
Route::get('/contact', [HomeController::class, 'contact']);
Route::get('/blog', [HomeController::class, 'blog']);
Route::get('/sales', [HomeController::class, 'sales']);
Route::get('/product', [HomeController::class, 'product']);
Route::get('/track', [HomeController::class, 'track']);
Route::get('/stock', [HomeController::class, 'stock']);



Route::post('/add-product', [HomeController::class, 'addProduct'])->name('add.product');

// routes/web.php
Route::get('/products-list', [HomeController::class, 'getProducts']);
Route::delete('/delete-product/{id}', [HomeController::class, 'deleteProduct']);

Route::post('/add-stock', [HomeController::class, 'addStock']);
Route::get('/stock-list', [HomeController::class, 'getStockList']);


Route::get('/stock-list', [HomeController::class, 'getStockList']);
Route::delete('/delete-stock/{id}', [HomeController::class, 'deleteStock']);
Route::patch('/update-stock/{id}', [HomeController::class, 'updateStock']);

Route::get('/get-product-quantity/{id}', [HomeController::class, 'getQuantity']);


Route::post('/add-sale', [HomeController::class, 'addSale']);



Route::get('/track-sales-data', [HomeController::class, 'getTrackSalesData'])->name('track.sales.data');

Route::patch('/update-price/{id}', [HomeController::class, 'updatePrice']);


Route::get('/get-product-details/{id}', [HomeController::class, 'getProductDetails']);


  Route::get('/user/profile', [\Laravel\Jetstream\Http\Controllers\Livewire\UserProfileController::class, 'show'])
        ->name('profile.show');

// Route::resource('sales', HomeController::class);




// Route::post('/pay', [App\Http\Controllers\PaymentController::class, 'redirectToGateway'])->name('pay');

// // Laravel 8 & 9
// Route::get('/payment/callback', [App\Http\Controllers\PaymentController::class, 'handleGatewayCallback']);
// The route that the button calls to initialize payment
Route::post('/pay', [FlutterwaveController::class, 'initialize'])->name('pay');
// The callback url after a payment
Route::get('/rave/callback', [FlutterwaveController::class, 'callback'])->name('callback');


Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});
