<?php 

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Website\HomeController;

Route::domain(config('app.domain'))->group(function () {

    Route::get('/', [HomeController::class, 'index']);

});

