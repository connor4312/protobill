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
	 * Modifies the permissions array
	 * 
	 * @param string|array $permissions If an array, it will be merged with
	 *                                  existing permissions array.
	 * @param array $value
	 * @return self
	 */
	public function setPermission($permission, $value = array()) {
		if (is_array($permission)) {
			$this->permissions = array_merge_recursive($this->permissions, $permission);
		} else {
			$this->permissions[$permission] = $value;
		}

		return $this;
	}

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

		list($directive, $params) = $this->parseRuleString($rule);
		$filterMethodName = 'permission' . $directive;

		return call_user_func_array(array($this, $filterMethodName), $params);
	}

	/**
	 * Explodes the rule string and returns an array of directive
	 * (first elem) and any applicable arguments (second elem)
	 * 
	 * @param string $rule
	 * @return array
	 */
	protected function parseRuleString($rule) {

		$parts = explode(':', $rule, 2);

		$directive = $parts[0];
		if (count($parts) == 1) {
			$params = array();
		} else {
			$params = explode(',', $parts[1]);
		}

		return array($directive, $params);
	}

	/**
	 * Magic method designed to catch and filter calls to
	 * protected methods of child classes
	 * 
	 * @param string $method
	 * @param array $arguments
	 * @return mixed
	 */
	function __call($method, $arguments) {

		return $this->executePermission($method, $arguments);
	}

	/**
	 * Executes the given method and arguments on the child class
	 * 
	 * @param string $method
	 * @param array $arguments
	 * @return mixed
	 */
	protected function executePermission($method, $arguments) {

		if ($this->permissionCanAccess($method, $arguments)) {
			return call_user_func_array(array($this, $method), $arguments);
		} else {
			return Response::json(array(), 403);
		}
	}
}