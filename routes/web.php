<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RedirectController;

Route::get('/', function () {
    return view('welcome');
});



Route::get("/r/{slug}", [RedirectController::class, "redirect"])->name("redirect");
