<?php

class AuthenticationController extends BaseController {

	/**
	 * The Repository\AuthenticationRepositoryInterface object passed to the constructor.
	 * 
	 * @var 	Repository\AuthenticationRepositoryInterface
	 */
	protected $auth;

	/**
	 * The constructor for the AuthenticationController; an AuthenticationRepositoryInterface object is necessary.
	 * 
	 * @access 	public
	 * @param	$auth 	Repository\AuthenticationRepositoryInterface 	The ARI object.
	 * @return 	AuthenticationController
	 */
	public function __construct(Repository\AuthenticationRepositoryInterface $auth) {
		$this->auth = $auth;
	}

	/**
	 * Attempts to authenticate the user.
	 *
	 * @access 	public
	 * @return 	Response
	 */
	public function index() {
		
		if ($user = $this->auth->validate(Input::get('email'), Input::get('password'))) {
			return Response::json($user, 200);
		} else {
			return Response::json(array(), 401);
		}
	}

	/**
	 * Grabs the currently logged in user, if any.
	 * 
	 * @access 	public
	 * @return 	Response
	 */
	public function current() {
		if ($user = $this->auth->current()) {
			return Response::json($user, 200);
		} else {
			return Response::json(array(), 401);
		}
	}

	/**
	 * Logs the user out and flushes the session completely.
	 * 
	 * @access 	public
	 * @return 	Response
	 */
	public function logout() {

		$this->auth->logout();

		return Response::json(array(), 200);
	}
}
