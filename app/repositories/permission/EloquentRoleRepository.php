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
			return $role;
		} else {
			return Response::json(array(), 400);
		}

		return $role;
	}

	public function edit($id, $input) {

		$role = Role::find($id);
		$role->name = $input['name'];
		if ($role->save()) {
			return $role;
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

	protected function getRoleList($modifier = null) {

		$query = $this->generateQuery();
		$this->applyModifier($query, $modifier);
		
		return $this->formatResults($query->get());
	}

	protected function generateQuery() {

		return DB::table($this->roletable)
			->select($this->roletable . '.*')
			->join('permission_role', 'permission_role.role_id', '=', $this->roletable . '.id');
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

			if (array_key_exists($results['id'], $out)) {
				$out[$result['id']] = $result + array('permissions' => array());
			}

			$out[$result['id']]->permissions[] = $out;
		}

		return array_values($out);
	}
}