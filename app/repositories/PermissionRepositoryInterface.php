<?php namespace Repository;

interface PermissionRepositoryInterface {
	public function all();
	public function show($id);
}