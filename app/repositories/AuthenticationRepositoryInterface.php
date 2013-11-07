<?php namespace Repository;

interface AuthenticationRepositoryInterface {
	public function validate();
	public function current();
	public function logout();
}