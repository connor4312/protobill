<?php

class AuthenticationController extends BaseController {

	protected $user;

	public function __construct(Repository\UserRepositoryInterface $users) {
		$this->user = $users;
	}

	/**
	 * Attempts to authenticate the user
	 *
	 * @return Response
	 */
	public function index()
	{
		$email = Input::get('email');
		$password = Input::get('password');

        if ($user = $this->attempt($email, $password)) {
        	return Response::json($this->user->getCurrentUser(), 200);
        } else {
        	return Response::json(array(), 200);
        }
	}

	/**
	 * Authenticates the user, returning true on success
	 * 
	 * @param string $email
	 * @param string $password
	 * @return bool
	 */
	protected function attempt($email, $password) {

		$data = compact('email', 'password');

		if (Auth::attempt($data)) {
			return true;
		}

		return false;
	}

	/**
	 * Grabs the currently logged in user, if any
	 * 
	 * @return Response
	 */
	public function current() {
		if (Auth::guest()) {
			return Response::json(array(), 200);
		} else {
			return Response::json($this->user->getCurrentUser(), 200);
		}
	}

	/**
	 * Logs the user out and flushes the session
	 * 
	 * @return Response
	 */
	public function logout() {

		Auth::logout();

		Session::flush();
		Session::regenerate();

		return Response::json(array(), 200);
	}
}
