<?php
/**
 * |--------------------------------------------------------------------------
 * | Application & Route Filters
 * |--------------------------------------------------------------------------
 * |
 * | Below you will find the "before" and "after" events for the application
 * | which may be used to do any work before or after a request into your
 * | application. Here you may also register your custom route filters.
 * |
 */

use Illuminate\Support\Facades\Auth;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

App::before(function ($request) {

	$logFile = storage_path() . '/logs/request.log';

	$log = 'Method ' . $request->method() . ' Path ' . $request->path();

	$monolog = new Logger('log');
	$monolog->pushHandler(new StreamHandler($logFile), Logger::INFO);
	$monolog->info($log, compact('bindings', 'time'));

	if($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
		$statusCode = 204;

		$headers = array(
			'Access-Control-Allow-Origin'      => '*/*',
			'Access-Control-Allow-Methods'     => 'GET, POST, OPTIONS',
			'Access-Control-Allow-Headers'     => 'Origin, Content-Type, Accept, Authorization, X-Requested-With',
			'Access-Control-Allow-Credentials' => 'true'
			);

		return Response::make(null, $statusCode, $headers);
	}
});

App::after(function ($request, $response) {
	if (isset($_SERVER['HTTP_ORIGIN'])) {
		header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
		header('Access-Control-Allow-Credentials: true');
		header('Access-Control-Max-Age: 86400'); 
	}

	if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {

		if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
			header("Access-Control-Allow-Methods: GET, POST, OPTIONS");         

		if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
			header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");

		exit(0);
	}

});

/**
 * |--------------------------------------------------------------------------
 * | Authentication Filters
 * |--------------------------------------------------------------------------
 * |
 * | The following filters are used to verify that the user of the current
 * | session is logged into this application. The "basic" filter easily
 * | integrates HTTP Basic authentication for quick, simple checking.
 * |
 */

Route::filter('auth', function () {
	if (Auth::guest()) {
		if (Request::ajax()) {
			return Response::make('Unauthorized', 401);
		}
		else {
			//                return Redirect::guest('login');
			return Redirect::guest('/');
		}
	}
});

Route::filter('auth.basic', function () {
	return Auth::basic('username');
});

Route::filter('basic.once', function () {
	return Auth::onceBasic();
});

Route::filter('admin', function () {
	if (!(Auth::check() && Auth::user()->perfil == 'ADM')) {
		return Redirect::to('/');
	}
});

/**
 * |--------------------------------------------------------------------------
 * | Guest Filter
 * |--------------------------------------------------------------------------
 * |
 * | The "guest" filter is the counterpart of the authentication filters as
 * | it simply checks that the current user is not logged in. A redirect
 * | response will be issued if they are, which you may freely change.
 * |
 */

Route::filter('guest', function () {
	if (Auth::check()) {
		return Redirect::to('/');
	}
});

/**
 * |--------------------------------------------------------------------------
 * | CSRF Protection Filter
 * |--------------------------------------------------------------------------
 * |
 * | The CSRF filter is responsible for protecting your application against
 * | cross-site request forgery attacks. If this special token in a user
 * | session does not match the one given in this request, we'll bail.
 * |
 */

Route::filter('csrf', function () {
	if (Request::forged()) {
		return Response::error('500');
	}
	if (Session::token() != Input::get('_token')) {
		throw new Illuminate\Session\TokenMismatchException;
	}
});