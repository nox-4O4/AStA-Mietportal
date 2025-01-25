<?php

	use Illuminate\Support\Facades\Route;

	Route::get('/login', function () {
		return view('pages.login');
	});

	Route::get('/reset-password', function () {
		return view('pages.reset-password');
	});

