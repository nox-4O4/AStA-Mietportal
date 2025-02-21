<?php

	namespace App\Http\Middleware;

	use Closure;
	use Illuminate\Http\Request;
	use Symfony\Component\HttpFoundation\Response;

	class CommonHeaders {
		/**
		 * Handle an incoming request.
		 *
		 * @param Closure(Request): (Response) $next
		 */
		public function handle(Request $request, Closure $next): Response {
			$response = $next($request);
			$response->headers->set('Cross-Origin-Opener-Policy', 'same-origin');

			return $response;
		}
	}
