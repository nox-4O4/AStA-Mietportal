<?php

	namespace App\Http\Controllers;

	use Illuminate\Http\RedirectResponse;
	use Illuminate\Support\Facades\Auth;

	class LogoutController extends Controller {

		public function action(): RedirectResponse {
			if(Auth::user()) {
				Auth::guard('web')->logout();

				session()->invalidate();
				session()->regenerateToken();
			}

			return redirect()->route('shop');
		}
	}
