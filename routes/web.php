<?php

use App\Events\OrderMatched;
use App\Events\Test;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('welcome');
});


Route::get('/{vue}', function(){
    return view('welcome');
});