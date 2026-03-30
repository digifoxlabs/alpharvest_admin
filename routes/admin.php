<?php 

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;

// Route::domain(config('app.admin_subdomain') . '.' . config('app.domain'))->name('admin.')
Route::domain(config('app.admin_subdomain'))->name('admin.')
    // ->middleware(['auth:admin'])
    ->group(function () {


        Route::get('/', function () {
            return 'Admin Root Works';
        });


        Route::get('/dashboard', function () {
             return 'Admin/Dashboard Root Works';
        });


    });
