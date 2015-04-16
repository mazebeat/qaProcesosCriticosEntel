<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\View;

/**
 * Class HomeController
 */
class HomeController extends ApiController
{
	/**
	 * @var
	 */
	private $credentials;

	/**
	 *
	 */
	public function __construct()
	{
		parent::__construct();
		$this->credentials = $this->credentials;
	}

	/**
	 * @return mixed
	 */
	public function index()
	{
		if (Auth::check()) {
			return Redirect::to('dashboard');
		}

		return View::make('index');
	}

	/**
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function login()
	{
		$this->credentials = Input::all();

		Session::put('user', $this->credentials);

		try {
			if (Auth::attempt($this->credentials)) {
				$this->setData(array(
//					               'user'    => $this->credentials,
'message' => array('Autentificación correcta'),
'estado'  => true
				               ));
			}
			else {
				$this->setData(array(
					               'message' => array('Autentificación fallida'),
					               'estado'  => false
				               ));
			}
		} catch (Exception $e) {
			$this->setData(array(
				               'message' => array($e->getMessage()),
				               'estado'  => false
			               ));
		}

		return Response::json($this->getData(), $this->getStatus(), $this->getHeaders());

	}

	/**
	 * @return mixed
	 */
	public function logout()
	{
		Auth::logout();
		Session::forget('credentials');
		Session::forget('user');

		return Redirect::to('/');
	}
}
