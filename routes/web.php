<?php

	use App\Http\Components\Authentication\Login;
	use App\Http\Components\Authentication\PasswordForgot;
	use App\Http\Components\Authentication\PasswordReset;
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
	use App\Http\Components\Dashboard\Settings\ContentDetail;
	use App\Http\Components\Dashboard\Settings\ContentList;
	use App\Http\Components\Dashboard\Settings\DisabledDateDetail;
	use App\Http\Components\Dashboard\Settings\DisabledDateList;
	use App\Http\Components\Dashboard\UserCreate;
	use App\Http\Components\Dashboard\UserDetail;
	use App\Http\Components\Dashboard\UserList;
	use App\Http\Components\Shop\Cart;
	use App\Http\Components\Shop\Item as ShopItem;
	use App\Http\Components\Shop\ItemList as ShopItemList;
	use App\Http\Controllers\LogoutController;
	use App\Http\Controllers\MiscController;
	use Illuminate\Support\Facades\Route;

	Route::group(['middleware' => 'guest'], function () {
		Route::get('/login', Login::class)->name('login');
		Route::get('/reset-password', PasswordForgot::class)->name('password.forgot');
		Route::get('/reset-password/{token}', PasswordReset::class)->name('password.reset');
	});

	// logout route is outside of auth middleware to prevent users from being redirected to logout route on login when they called /logout while not authenticated
	Route::get('logout', [LogoutController::class, 'action'])->name('logout');

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
			Route::get('/', fn() => redirect()->route('dashboard.settings.disabledDates.list'));

			Route::group(['prefix' => '/disabledDates', 'as' => '.disabledDates'], function () {
				Route::get('/', DisabledDateList::class)->name('.list');
				Route::get('/create', DisabledDateDetail::class)->name('.create');
				Route::get('/edit/{disabledDate}', DisabledDateDetail::class)->name('.edit');
			});

			Route::group(['prefix' => '/contents', 'as' => '.contents'], function () {
				Route::get('/', ContentList::class)->name('.list');
				Route::get('/edit/{content}', ContentDetail::class)->name('.edit');
			});

		});
	});

	// Setting custom missing callback prevents SubstituteBindings from throwing an exception which breaks Livewire component updates on error pages.
	Route::group(['missing' => app(MiscController::class)->notFound(...), 'as' => 'shop'], function () {
		Route::get('/', ShopItemList::class);

		Route::get('/artikel/{item}/{slug?}', ShopItem::class)
		     ->name('.item.view')
		     ->can('view', 'item');

		Route::get('/artikelgruppe/{group}/{slug?}', ShopItem::class)
		     ->name('.itemGroup.view')
		     ->can('view', 'group');

		Route::get('/warenkorb', Cart::class)->name('.cart');
		Route::get('/checkout', fn() => 'Hier ist der Checkout.')->name('.checkout');
	});

	Route::fallback([MiscController::class, 'notFound']); // fallback route is required to get middlewares executed on 404 page (otherwise session, auth, csrf-token, etc. won't be available)
