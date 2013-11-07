<?php namespace Repository;

use Response;

abstract class PermissionsLayer {

	/** @type \Mode\User|null The context permissions will be checked in */
	protected $permissionsUser;

	/**
	 * @type array
	 * 
	 * This is the grand array of permissions. Key should be method names
	 * to apply to. Values may be strings, functions, or arrays of both.
	 * Funtions have an array of arguments passed in, and are expected to
	 * return an array. For example, the following key would restrict
	 * access to function `bar` to Admin users who have the permission
	 * AccessBar. It also has a filter function that passes the args
	 * without modification.
	 * 
	 * 	'bar' => array(
	 * 		'HasRole:Admin',
	 * 		'Can:AccessBar',
	 * 		function($args) { return $args; }
	 * 	);
	 * 
	 * Rules are checked and arguments are filtered in the order in
	 * which they are defined. Methods not defined herein
	 * cannot be called.
	 */
	protected $permissions = array();

	/**
	 * Sets the user to use for permissions checking
	 * 
	 * @param \Model\User
	 * @return PermissionsLayer
	 */

	public function setUserContext($user) {

		$this->permissionsUser = $user;

		return $this;
	}

	/**
	 * Checks if the given user (if any) has the given role.
	 * 
	 * @param string $role
	 * @return bool
	 */
	protected function permissionHasRole($role) {

		if ($role == 'Guest') {
			return $this->permissionsUser ? false : true;
		}

		if (!$this->permissionsUser) {
			return false;
		}

		if ($role == 'User') {
			return true;
		}

		return $this->permissionsUser->hasRole($role);
	}

	/**
	 * Checks to see if the given user has permissions for the action.
	 * 
	 * @param string $action
	 * @return bool
	 */
	protected function permissionCan($action) {

		if (!$this->permissionsUser) {
			return false;
		}

		return $this->permissionsUser->can($action);
	}

	/**
	 * Checks if the method can be called
	 * 
	 * @param string $method
	 * @param array &$arguments
	 * @return bool
	 */
	protected function permissionCanAccess($method, &$arguments) {

		if (!array_key_exists($method, $this->permissions)) {
			return false;
		}

		$rules = $this->permissions[$method];

		if (is_array($rules)) {
			foreach ($this->permissions[$method] as $rule) {
				if (!$this->processRule($rule, $arguments)) {
					return false;
				}
			}
		} elseif (!$this->processRule($rules, $arguments)) {
			return false;
		}

		return true;
	}

	/**
	 * Processes the give rule or filter, returning false on failure
	 * 
	 * @param mixed $rule
	 * @param array &$arguments
	 * @return bool
	 */
	protected function processRule($rule, &$arguments) {

		if (is_string($rule)) {
			list($directive, $param) = explode(':', $rule, 1);
			$filterMethodName = 'permission' . $directive;

			return $this->$filterMethodName($param);
		} else {
			$arguments = $rule($arguments);
		}

		return true;
	}

	/**
	 * Magic method designed to filter and restict
	 * access to protected methods
	 * 
	 * @param string $method
	 * @param array $arguments
	 * @return mixed
	 */
	function __call($method, $arguments) {

		if ($this->permissionCanAccess($method, $arguments)) {
			return call_user_method_array($method, $this, $arguments);
		} else {
			return Response::json(array(), 403);
		}
	}
}