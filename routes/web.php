<?php

	use App\Http\Components\Authentication\Login;
	use App\Http\Components\Authentication\PasswordForgot;
	use App\Http\Components\Authentication\PasswordReset;
	use App\Http\Components\Dashboard\ItemList;
	use App\Http\Components\Dashboard\Orders;
	use App\Http\Components\Dashboard\Profile;
	use App\Http\Components\Dashboard\UserDetail;
	use App\Http\Components\Dashboard\UserList;
	use App\Http\Controllers\Logout;
	use App\Models\Item;
	use Illuminate\Support\Facades\Route;

	Route::group(['middleware' => 'guest'], function () {
		Route::get('/login', Login::class)->name('login');
		Route::get('/reset-password', PasswordForgot::class)->name('password.forgot');
		Route::get('/reset-password/{token}', PasswordReset::class)->name('password.reset');
	});

	// logout route is outside of auth middleware to prevent users from being redirected to logout route on login when they called /logout while not authenticated
	Route::get('logout', [Logout::class, 'action'])->name('logout');

	Route::group(['middleware' => 'auth', 'prefix' => 'dashboard', 'as' => 'dashboard'], function () {
		Route::get('/', fn() => redirect()->route(config('app.dashboard.defaultRoute')));

		Route::get('/orders', Orders::class)->name('.orders');
		Route::get('/profile', Profile::class)->name('.profile');

		Route::get('/items', ItemList::class)->name('.items.list');
		Route::get('/items/{item}', fn(Item $item) => "Details zu $item->name")->name('.items.edit');

		Route::group(['middleware' => 'can:manage-users'], function () {
			Route::get('/users', UserList::class)->name('.users.list');
			Route::get('/users/{user}', UserDetail::class)->name('.users.edit');
		});
	});

	Route::get('/', fn() => 'Das hier ist der Shop. <a href="' . route('dashboard') . '">Zum Dashboard</a>')->name('shop');
