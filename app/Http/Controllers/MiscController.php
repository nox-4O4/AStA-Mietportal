<?php

	namespace App\Http\Controllers;

	class MiscController extends Controller {

		public function notFound() {
			return response()->view('errors.404')->setStatusCode(404);
		}
	}
