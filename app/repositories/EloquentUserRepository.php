<?php namespace Repository;

use Model\User;

class EloquentUserRepository implements UserRepositoryInterface {
	
	public function all() {
		return User::all();
	}

	public function store() {

	}
}