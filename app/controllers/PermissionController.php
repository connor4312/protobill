<?php

class PermissionController extends BaseController {

	/**
	 * The Repository\PermissionRepositoryInterface object for the PermissionController
	 * 
	 * @var 	$permission
	 */
	protected $permission;

	/**
	 * Creates the PermissionController instance; this constructor passes a PermissionRepositoryInterface object.
	 * 
	 * @access 	public
	 * @param 	Repository\PermissionRepositoryInterface 	$permission 	The permission object.
	 * @return 	PermissionController
	 */
	public function __construct(Repository\PermissionRepositoryInterface $permission) {
		$this->permission = $permission;
	}

	/**
	 * Shows a list of all the permissions.
	 *
	 * @access 	public
	 * @return 	string 	The representation of all the permissions.
	 */
	public function index()
	{
        return $this->permission->all();
	}

	/**
	 * Shows a specific permission based by $id.
	 *
	 * @access 	public
	 * @param  	int 	$id 	The permission ID to display.
	 * @return 	The requested permission.
	 */
	public function show($id)
	{
        return $this->permission->show($id);
	}

}
