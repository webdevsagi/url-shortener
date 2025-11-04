<?php
use App\Http\Controllers\Admin\LinkController as AdminLinkController;
use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RedirectController;

Route::get('/', function () {
    return view('auth.login');
});


Route::get("/r/{slug}", [RedirectController::class, "redirect"])->name("redirect");
Route::get("/login", [LoginController::class, "showLoginForm"])->name("login");
Route::post("/login", [LoginController::class, "login"]);
Route::post("/logout", [LoginController::class, "logout"])->name("logout");

// נתיבי אדמין
Route::middleware(["admin"])->prefix("admin")->name("admin.")->group(function () {
    Route::get("/links", [AdminLinkController::class, "index"])->name("links.index");
});
