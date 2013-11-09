<?php

class PermissionController extends BaseController {

	protected $permission;

	public function __construct(Repository\PermissionRepositoryInterface $permission) {
		$this->permission = $permission;
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
        return $this->permission->all();
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
        return $this->permission->show($id);
	}

}