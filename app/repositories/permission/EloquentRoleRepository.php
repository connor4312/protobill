<?php namespace Repository;

use Model\Role;
use DB;
use Response;

class EloquentRoleRepository implements RoleRepositoryInterface {

	protected $roletable;

	function __construct() {
		$this->roletable = (new Role)->getTable();
	}
	
	public function all() {
		return $this->getRoleList();
	}

	public function show($id) {

		$result = $this->getRoleList(function($query) use ($id) {
			$query->where($this->roletable . '.id', $id);
		});

		return (array) array_shift($result);
	}

	public function create($input) {

		$role = new Role;
		$role->name = $input['name'];
		if ($role->save()) {

			$this->updatePermissionsLinks($id, $input['permissions']);
			return $this->show($role->id);
			
		} else {
			return Response::json(array(), 400);
		}

		return $role;
	}

	public function edit($id, $input) {

		$role = Role::find($id);
		$role->name = $input['name'];
		if ($role->save()) {

			$this->updatePermissionsLinks($id, $input['permissions']);
			return $this->show($role->id);
			
		} else {
			return Response::json(array(), 400);
		}
	}

	public function delete($id) {

		if ($role = Role::find($id)) {
			$role->delete();
		} else {
			return Response::json(array(), 400);
		}
	}

	protected function roleLinkingTable() {
		return DB::table('permission_role');
	}

	protected function updatePermissionsLinks($id, $input) {

		$data = $this->getRoleList(function($query) use ($id) {
			$query->where($this->roletable . '.id', $id);
		});

		$this->addPermissions($data[0]['permissions'], $input, $id);
		$this->deletePermissions($data[0]['permissions'], $input, $id);
	}

	protected function addPermissions($existing, $new, $role_id) {

		$addPermissions = array_diff($new, $existing);

		$inserts = array();
		foreach ($addPermissions as $permission_id) {
			$inserts[] = compact('role_id', 'permission_id');
		}

		$this->roleLinkingTable()->insert($inserts);
	}

	protected function deletePermissions($existing, $new, $role_id) {

		$deletePermissions = array_diff($existing, $new);

		$this->roleLinkingTable()
			->where('role_id', $role_id)
			->where(function($query) use ($deletePermissions) {
				foreach ($deletePermissions as $permission_id) {
					$query->where('permission_id', '=', $permission_id, 'or');
				}
			})->delete();
	}

	protected function getRoleList($modifier = null) {

		$query = $this->generateQuery();
		$this->applyModifier($query, $modifier);
		
		return $this->formatResults($query->get());
	}

	protected function generateQuery() {

		return DB::table($this->roletable)
			->select($this->roletable . '.*', 'permission_role.permission_id')
			->leftjoin('permission_role', 'permission_role.role_id', '=', $this->roletable . '.id');
	}

	protected function applyModifier($query, $modifier) {

		if ($modifier) {
			$modifier($query);
		}
	}

	protected function formatResults($results) {

		$out = array();

		foreach ($results as $result) {
			$result = get_object_vars($result);

			if (!array_key_exists($result['id'], $out)) {
				$out[$result['id']] = $result + array('permissions' => array());
			}

			if ($result['permission_id']) {
				$out[$result['id']]['permissions'][] = $result['permission_id'];
			}
		}

		return array_values($out);
	}
}