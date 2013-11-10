<?php namespace Repository;

use Model\Permission;

class EloquentPermissionRepository implements PermissionRepositoryInterface {
	
	public function all() {
		return Permission::all();
	}

	public function show($id) {
		return Permission::find($id);
	}
}