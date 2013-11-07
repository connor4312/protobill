<?php

class AuthenticationController extends BaseController {

	protected $auth;

	public function __construct(Repository\AuthenticationRepositoryInterface $auth) {
		$this->auth = $auth;
	}

	/**
	 * Attempts to authenticate the user
	 *
	 * @return Response
	 */
	public function index() {
		
		if ($user = $this->auth->validate(Input::get('email'), Input::get('password'))) {
			return Response::json($out, 200);
		} else {
			return Response::json(array(), 401);
		}
	}

	/**
	 * Grabs the currently logged in user, if any
	 * 
	 * @return Response
	 */
	public function current() {
		if ($user = $this->auth->current()) {
			return Response::json($user, 200);
		} else {
			return Response::json(array(), 401);
		}
	}

	/**
	 * Logs the user out and flushes the session
	 * 
	 * @return Response
	 */
	public function logout() {

		$this->auth->logout()

		return Response::json(array(), 200);
	}
}
