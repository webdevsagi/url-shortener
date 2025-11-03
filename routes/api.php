<?php
use App\Http\Controllers\Api\LinkController;
use Illuminate\Support\Facades\Route;

Route::middleware(["api.key", "throttle:30,1"])->group(function () {
    Route::post("/links", [LinkController::class, "store"]);
});
