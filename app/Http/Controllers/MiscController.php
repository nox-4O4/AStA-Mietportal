<?php

	namespace App\Http\Controllers;

	use App\Util\Helper;
	use Cache;
	use Illuminate\Support\Facades\Storage;
	use Symfony\Component\HttpFoundation\HeaderUtils;
	use Symfony\Component\HttpFoundation\Response;

	class MiscController extends Controller {

		public function notFound(): Response {
			return response()->view('errors.404')->setStatusCode(404);
		}

		public function termsOfService(): Response {
			if(!Storage::fileExists('tos.pdf'))
				Cache::forget('tosRendered');

			// Update PDF file when cache was cleared
			Cache::rememberForever('tosRendered', function () {
				Storage::put('tos.pdf', Helper::renderPDFTemplate('pdfs.tos')->output());
				return true;
			});

			return response()
				->download(Storage::path('tos.pdf'), 'Mietordnung.pdf', disposition: HeaderUtils::DISPOSITION_INLINE);
		}
	}
