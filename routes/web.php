<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    abort(404);
});

// require __DIR__.'/api.php';
