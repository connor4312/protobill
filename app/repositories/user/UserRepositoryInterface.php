<?php namespace Repository;

interface UserRepositoryInterface {
	public function all();
	public function store();
	public function getCurrentUser();
}