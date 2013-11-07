<?php namespace Repository;

interface AuthenticationRepositoryInterface {
	public function validate($username, $password);
	public function current();
	public function logout();
}