<?php namespace Repository;

interface RoleRepositoryInterface {
	public function all();
	public function show($id);
	public function create($input);
	public function delete($id);
	public function edit($id, $input);
}