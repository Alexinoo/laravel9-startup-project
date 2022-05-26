<?php

use App\Http\Controllers\Admin\AdminController;
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

Route::get('/dashboard', function () {
    return view('Admin.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__.'/auth.php';


Route::controller(AdminController::class)->group(function(){

    // Logout
    Route::get('admin/logout' , 'destroy')->name('admin.logout');

    // Manage Profile
    Route::get('admin/profile' , 'profile')->name('admin.profile');
    Route::get('admin/edit-profile/{id}' , 'EditProfile')->name('admin_profile.edit');
    Route::post('admin/store-profile' , 'store')->name('admin_profile.store');

    // Change Password
    Route::get('admin/change-password', 'ChangePassword')->name('change.password');
    Route::post('admin/update-password', 'UpdatePassword')->name('update.password');
});