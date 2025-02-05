<?php

	use App\Http\Components\Authentication\Login;
	use App\Http\Components\Authentication\PasswordForgot;
	use App\Http\Components\Authentication\PasswordReset;
	use App\Http\Components\Dashboard\DisabledDate;
	use App\Http\Components\Dashboard\Dummy;
	use App\Http\Components\Dashboard\Items\ItemCreate;
	use App\Http\Components\Dashboard\Items\ItemDetail;
	use App\Http\Components\Dashboard\Items\ItemGroupCreate;
	use App\Http\Components\Dashboard\Items\ItemGroupDetail;
	use App\Http\Components\Dashboard\Items\ItemGroupList;
	use App\Http\Components\Dashboard\Items\ItemList;
	use App\Http\Components\Dashboard\Orders\OrderDetailView;
	use App\Http\Components\Dashboard\Orders\OrderList;
	use App\Http\Components\Dashboard\Profile;
	use App\Http\Components\Dashboard\Settings;
	use App\Http\Components\Dashboard\UserCreate;
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

		Route::get('/profile', Profile::class)->name('.profile');

		Route::group(['prefix' => '/items', 'as' => '.items'], function () {
			Route::get('/', ItemList::class)->name('.list');
			Route::get('/create', ItemCreate::class)->name('.create');
			Route::get('/{item}', ItemDetail::class)->name('.edit');
		});

		Route::group(['prefix' => '/groups', 'as' => '.groups'], function () {
			Route::get('/', ItemGroupList::class)->name('.list');
			Route::get('/create', ItemGroupCreate::class)->name('.create');
			Route::get('/edit/{group}', ItemGroupDetail::class)->name('.edit');
		});

		Route::group(['prefix' => '/orders', 'as' => '.orders'], function () {
			Route::get('/', OrderList::class)->name('.list');
			Route::get('/create', Dummy::class)->name('.create');
			Route::get('/view/{order}', OrderDetailView::class)->name('.view');
		});

		Route::group(['prefix' => '/reports', 'as' => '.reports'], function () {
			Route::get('/', fn() => redirect()->route('dashboard.reports.availability'));
			Route::get('/availability', Dummy::class)->name('.availability');
			Route::get('/last-bookings', Dummy::class)->name('.last-bookings');
		});

		Route::group(['middleware' => 'can:manage-users', 'prefix' => '/users', 'as' => '.users'], function () {
			Route::get('/', UserList::class)->name('.list');
			Route::get('/create', UserCreate::class)->name('.create');
			Route::get('/edit/{user}', UserDetail::class)->name('.edit');
		});

		Route::group(['prefix' => '/settings', 'as' => '.settings'], function () {
			Route::get('/', Settings::class)->name('.view');
			Route::get('/disabledDates/edit/{disabledDate}', DisabledDate::class)->name('.disabledDates.edit');
			Route::get('/disabledDates/create', DisabledDate::class)->name('.disabledDates.create');
		});
	});

	Route::get('/', fn() => 'Das hier ist der Shop. <a href="' . route('dashboard') . '">Zum Dashboard</a>')->name('shop');
	Route::get('/artikel/{item}/{slug}', fn(Item $item, string $slug) => "Das hier ist die Artilelseite zu $item->name im Shop.<br><a href=\"" . route('dashboard') . '">Zum Dashboard</a>')->name('shop.article.view');
