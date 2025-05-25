<?php

use App\Domains\Snake\Configuration\Details;
use App\Domains\Snake\Configuration\HexColor;
use App\Http\Controllers\EndController;
use App\Http\Controllers\MoveController;
use App\Http\Controllers\StartController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn() => new Details(
    author: "",
    color: new HexColor("#000000"),
    head: "",
    tail: "",
    version: "0.0.1",
    apiVersion: "1",
)->toArray());

Route::post('/move', MoveController::class);
Route::post('/start', StartController::class);
Route::post('/end', EndController::class);
