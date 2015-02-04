<?php

use Illuminate\Support\Facades\View;

/**
 * Class AdminController
 */
class AdminController extends ApiController
{
	/**
	 *
	 */
	function __construct()
	{
		parent::__construct();
	}

	/**
	 * @return mixed
	 */
	public function index()
	{
		return View::make('dashboard.index');
	}

	/**
	 * @return mixed
	 */
	public function authUser() {
		return Session::get('user', array());
	}
}
