<?php

use App\Http\Controllers\AuthController;
use App\Http\Middleware\StudentMiddleware;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});
Route::get('/register',[AuthController::class,'loadRegister']);
Route::post('/register', [AuthController::class, 'studentRegister'])->name('studentRegister');

Route::get('/', [AuthController::class, 'loadLogin']);
Route::post('/login', [AuthController::class, 'userlogin'])->name('userlogin');
Route::get('/login', function (){
    return redirect('/');
});

Route::get('/logout', [AuthController::class, 'logout']);
Route::get('/forget_pass', [AuthController::class, 'forgetpassLoading']);
Route::post('/forget_pass', [AuthController::class, 'forgetpassword'])->name('forgetpassword');
Route::get('/reset-password', [AuthController::class, 'resetpassLoad']);
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('resetPassword');



// admin middleware group
Route::group(['middleware'=>['web','checkAdmin']],function(){
    Route::get('/admin/dashboard', [AuthController::class, 'admin_dashboard']);
    
});
//  StudentMiddleware group
Route::group(['middleware' => ['web', 'checkStudent']], function () {
    Route::get('/dashboard', [AuthController::class, 'dashboard']);
});