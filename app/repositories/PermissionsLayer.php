<?php namespace Repository;

use Response;

abstract class PermissionsLayer {

	/**
	 * @var \Mode\User|null The context permissions will be checked in
	 */
	protected $permissionsUser;

	/**
	 * This is the grand array of permissions. Key should be method names
	 * to apply to. Values may be strings, functions, or arrays of both.
	 * Funtions have an array of arguments passed in by reference,  and
	 * are expected to return a boolean (false to deny the request).
	 * For example, the following key would restrict access to
	 * function `bar` to Admin users who have the permission
	 * AccessBar. It also has a filter function that allows
	 * all requests and does not modify the arguments.
	 * 
	 * 	'bar' => array(
	 * 		'HasRole:Admin',
	 * 		'Can:AccessBar',
	 * 		function(&$args) { return true; }
	 * 	);
	 * 
	 * Rules are checked and arguments are filtered in the order in
	 * which they are defined. Methods not defined herein
	 * cannot be called.
	 * 
	 * @var array
	 */
	protected $permissions = array();

	/**
	 * Sets the user to use for permissions checking
	 * 
	 * @param \Model\User
	 * @return self
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
	 * Processes the give rule or filter, returning
	 * the results of the filter
	 * 
	 * @param string|closure $rule
	 * @param array &$arguments
	 * @return bool
	 */
	protected function processRule($rule, &$arguments) {

		if (is_string($rule)) {
			return $this->processStringRule($rule, $arguments);
		} else {
			return $rule($arguments);
		}

		return true;
	}

	/**
	 * Dispatch the rule defined by the given string, returning results
	 * 
	 * @param string $rule
	 * @param array $arguments
	 * @return bool
	 */
	protected function processStringRule($rule, $arguments) {

		list($directive, $param) = explode(':', $rule, 1);
		$filterMethodName = 'permission' . $directive;

		return $this->$filterMethodName($param);
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