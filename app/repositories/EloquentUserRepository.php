<?php namespace Repository;

use Model\User;
use Auth;

class EloquentUserRepository implements UserRepositoryInterface {
	
	public function all() {
		return User::all();
	}

	public function store() {

	}

	/**
	 * Gets the currently logged in user.
	 * 
	 * @return \Model\User
	 */
	public function getCurrentUser() {
		return Auth::user();
	}
}