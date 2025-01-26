<?php

	use App\Http\Components\Authentication\Login;
	use App\Http\Components\Authentication\PasswordForgot;
	use App\Http\Components\Authentication\PasswordReset;
	use App\Http\Components\Orders;
	use App\Http\Components\Profile;
	use App\Http\Controllers\Logout;
	use Illuminate\Support\Facades\Route;

	Route::group(['middleware' => 'guest'], function () {
		Route::get('/login', Login::class)->name('login');
		Route::get('/reset-password', PasswordForgot::class)->name('password.forgot');
		Route::get('/reset-password/{token}', PasswordReset::class)->name('password.reset');
	});

	// logout route is outside of auth middleware to prevent users from being redirected to logout route on login when they called /logout while not authenticated
	Route::get('logout', [Logout::class, 'action'])->name('logout');

	Route::group(['middleware' => 'auth'], function () {
		Route::get('/orders', Orders::class)->name('orders');
		Route::get('/profile', Profile::class)->name('profile');
	});

	Route::get('/', fn() => 'Das hier ist der Shop. <a href="' . route('login') . '">Zum Dashboard</a>')->name('shop');
