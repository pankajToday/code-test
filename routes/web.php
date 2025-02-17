<?php


use App\Http\Controllers\TestController;
use Illuminate\Support\Facades\Route;


Route::inertia('/', 'Contact');


Route::middleware([
    'auth',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {

});

/**  end  **/
