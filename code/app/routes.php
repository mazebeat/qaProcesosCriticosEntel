<?php
// CROSS-DOMAIN
header('Access-Control-Allow-Origin: *');
// MEMORY
ini_set('memory_limit', '3500M');
ini_set('max_execution_time', '0');
ini_set('set_time_limit', '0');

// CHARSET
if (PHP_VERSION_ID < 50600) {
	iconv_set_encoding('input_encoding', 'UTF-8');
	iconv_set_encoding('output_encoding', 'UTF-8');
	iconv_set_encoding('internal_encoding', 'UTF-8');
}
else {
	ini_set('default_charset', 'UTF-8');
}

// XDEBUG
if (Config::get('app.debug')) {
	ini_set('xdebug.collect_vars', 'on');
	ini_set('xdebug.collect_params', '4');
	ini_set('xdebug.dump_globals', 'on');
	ini_set('xdebug.dump.SERVER', 'REQUEST_URI');

	// ERRORS
	error_reporting(E_ALL);
	ini_set('display_errors', true);
	ini_set('display_startup_errors', true);
}

/*
 * APP ROUTES
 */
Route::group(array('after' => 'auth'), function () {
	Route::get('/', 'HomeController@index');
	Route::post('login', 'HomeController@login');
});

Route::group(array('before' => 'auth'), function () {
	Route::get('logout', 'HomeController@logout');
	Route::group(array('prefix' => 'dashboard'), function () {
		//	Consolidados
		Route::get('/', 'ConsolidadoController@index');
		Route::group(array('prefix' => 'consolidado'), function () {
			//  Individual
			Route::get('individual', 'ConsolidadoController@consolidadoIndividual');
		});
		//	Consultas
		Route::group(array('prefix' => 'consulta'), function () {
			//	Individual
			Route::get('individual', 'ConsultaController@individual');
		});
		//	Informes
		Route::get('informes', 'InformeController@index');
		//	Administración
		Route::group(array('prefix' => 'admin'), function () {
			//	Usuarios
			Route::get('usuarios', 'AdminController@adminUsuarios');
			//	Carga de planes
			Route::get('carga/planes', 'AdminController@adminCargaPlanes');
		});
	});
	Route::post('gentable', function () {
		$data   = Input::get('data');
		$body   = array_get($data, 'body');
		$header = array_get($data, 'header');

		echo HTML::gentable('table', $body, $header, false, array(), array('class' => 'table table-hover table-condensed table-list-search'));
	});
	Route::post('processBase64', function () {
		$id   = Input::get('id', 0);
		$name = Input::get('name', '');

		try {
			if ($id > 0 && $name != '') {
				$response = Functions::curlRequest(Config::get('webservice.url') . 'Documento/postDocumento', array('id' => $id), 'POST');
				$base64   = Functions::hexToBase64($response->data->value);
				$csv      = base64_decode($base64);

				$headers = array(
					'Content-type'        => 'application/csv',
					'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
					'Content-type'        => 'text/csv',
					'Content-Disposition' => 'attachment; filename=' . $name,
					'Expires'             => '0',
					'Pragma'              => 'public'
				);

				return Response::json($csv);
				//			return Response::make(rtrim($csv, "\n"), 200, $headers);
			}
			else {
				throw new \Exception('Error: Something happened while trying to download file ' . $name . '<br>');
			}
		} catch (Exception $e) {
			throw new \Exception('Error: Something happened while trying to download file ' . $name . '<br>' . $e);
		}
	});
});

Route::post('test', function () {
	sleep(10);
});


