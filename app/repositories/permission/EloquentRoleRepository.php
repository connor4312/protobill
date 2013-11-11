<?php namespace Repository;

use Model\Role;
use DB;

class EloquentRoleRepository implements RoleRepositoryInterface {

	protected $roletable;

	function __construct() {
		$this->roletable = (new Role)->getTable();
	}
	
	/**
	 * List all roles
	 * 
	 * @return array
	 */
	public function all() {
		return $this->getRoleList();
	}

	/**
	 * Shows a single role by ID
	 * 
	 * @param int $id
	 * @return array
	 */
	public function show($id) {

		$result = $this->getRoleList(function($query) use ($id) {
			$query->where($this->roletable . '.id', $id);
		});

		return (array) array_shift($result);
	}

	/**
	 * Creates a role based off an array of input. 
	 * 
	 * 
	 * @param array $input Expected to pass a `permissions` key, containing an
	 *                     indexed array of permission ids.
	 * @return array|bool The newly created role model, or false on fail
	 */
	public function create($input) {

		$role = new Role;
		$role->name = $input['name'];

		if ($role->save()) {

			$this->updatePermissionsLinks($role->id, $input['permissions']);
			return $this->show($role->id);
			
		} else {
			return false;
		}
	}

	/**
	 * Edits a role by ID, with an array of attributes
	 * 
	 * @param array $input Expected to pass a `permissions` key, containing an
	 *                     indexed array of permission ids.
	 * @return array|bool The newly created role model, or false on fail
	 */
	public function edit($id, $input) {

		$role = Role::find($id);
		$role->name = $input['name'];
		if ($role->save()) {

			$this->updatePermissionsLinks($id, $input['permissions']);
			return $this->show($role->id);
			
		} else {
			return false;
		}
	}

	/**
	 * Deletes a role by ID
	 * 
	 * @param int $id
	 * @return bool
	 */
	public function delete($id) {

		if ($role = Role::find($id)) {
			$role->delete();
		} else {
			return false;
		}
	}

	/**
	 * Retrieves the permissions linking table from the query builder
	 * 
	 * @return Illuminate\Database\Query\Builder
	 */
	protected function roleLinkingTable() {
		return DB::table('permission_role');
	}

	/**
	 * Updates the linkages of permissions on a model
	 * 
	 * @param int $id ID of the model to update links for
	 * @param array $input Array of IDs to update to
	 */
	protected function updatePermissionsLinks($id, $input) {

		$data = $this->getRoleList(function($query) use ($id) {
			$query->where($this->roletable . '.id', $id);
		});

		$this->addPermissions($data[0]['permissions'], $input, $id);
		$this->deletePermissions($data[0]['permissions'], $input, $id);
	}

	/**
	 * Adds permissions to the model, based off an array comparison
	 * 
	 * @param array $existing The existing permissions on the model (IDs)
	 * @param array $new Array of new permissions to update to
	 * @param int $role_id The role to update
	 * @return void
	 */
	protected function addPermissions($existing, $new, $role_id) {

		$addPermissions = array_diff($new, $existing);

		$inserts = array();
		foreach ($addPermissions as $permission_id) {
			$inserts[] = compact('role_id', 'permission_id');
		}

		$this->roleLinkingTable()->insert($inserts);
	}

	/**
	 * Deletes permissions to the model, based off an array comparison
	 * 
	 * @param array $existing The existing permissions on the model (IDs)
	 * @param array $new Array of new permissions to update to
	 * @param int $role_id The role to update
	 * @return void
	 */
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

	/**
	 * Gets roles
	 * 
	 * @param Closure $modifier Query modifier
	 * @return array
	 */
	protected function getRoleList($modifier = null) {

		$query = $this->generateQuery();
		$this->applyModifier($query, $modifier);
		
		return $this->formatResults($query->get());
	}

	/**
	 * Generates the start of a query to get the permissions for role(s)
	 * 
	 * @return Illuminate\Database\Query\Builder
	 */
	protected function generateQuery() {

		return DB::table($this->roletable)
			->select($this->roletable . '.*', 'permission_role.permission_id')
			->leftjoin('permission_role', 'permission_role.role_id', '=', $this->roletable . '.id');
	}

	/**
	 * Stupid function to apply the modifier to a query
	 * 
	 * @param Illuminate\Database\Query\Builder $query
	 * @param Closure $modifier
	 * @return void
	 */
	protected function applyModifier($query, $modifier) {

		if ($modifier) {
			$modifier($query);
		}
	}

	/**
	 * Generates an array of role results from the give query
	 * 
	 * @param array $results Results from a query started with generateQuery()
	 * @return array List of permissions given  as an indexed array in the
	 *               `permissions` key
	 */
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