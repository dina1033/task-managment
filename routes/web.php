<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
\Illuminate\Support\Facades\Route::get('/debug-panels', function() {
    return \Filament\Facades\Filament::getPanels();
});
