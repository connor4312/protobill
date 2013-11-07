<?php namespace Repository;

class AuthAuthenticationRepository implements AuthenticationRepositoryInterface {

	public function __construct(Repository\UserRepositoryInterface $user) {
		$this->user = $user;
	}

	/**
	 * Attempts to authenticate the user
	 *
	 * @param string $email
	 * @param string $password
	 * @return \Model\User|bool
	 */
	public function validate($username, $password) {

		$data = compact('email', 'password');

		if (Auth::attempt($data)) {
			return $this->user->getCurrentUser();
		} else {
			return false;
		}
	}

	/**
	 * Grabs the currently logged in user, if any
	 * 
	 * @return \Model\User|bool
	 */
	public function current() {
		if (Auth::guest()) {
			return false;
		} else {
			return $this->user->getCurrentUser();
		}
	}

	/**
	 * Logs the user out and flushes the session
	 * 
	 * @return void
	 */
	public function logout() {

		Auth::logout();

		Session::flush();
		Session::regenerate();
	}
}