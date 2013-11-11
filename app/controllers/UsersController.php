<?php

class UsersController extends BaseController {

	protected $user;

	/**
	 *  Creates the UsersController instance. The UsersController handles much of general user functions.
	 * 
	 * @access 	public
	 * @param 	Repository\UserRepositoryInterface 	$users 	The UserRepositoryInterface used by the UsersController.
	 * @return 	UsersController
	 */
	public function __construct(Repository\UserRepositoryInterface $users) {
		$this->user = $users;
	}

	/**
	 * Shows all of the users.
	 *
	 * @return Model\User 	User Eloquent object.
	 */
	public function index()
	{
        return $this->user->all();
	}

	/**
	 * Creates a new User and stores it.
	 *
	 * @return Response
	 */
	public function store()
	{
        return View::make('users.create');
	}

	/**
	 * Shows the specific User's information.
	 *
	 * @param  int  $id 	The ID of the user.
	 * @return Response
	 */
	public function show($id)
	{
        return View::make('users.show');
	}

	/**
	 * Edits the specified User; User is specified by @param $id.
	 *
	 * @param  int  $id 	ID of the user which is needed to edit.
	 * @return Response
	 */
	public function edit($id)
	{
        return View::make('users.edit');
	}

	/**
	 * Updates the User in storage.
	 *
	 * @param  int  $id 	ID of the user to update.
	 * @return Response
	 */
	public function update($id)
	{
		//
	}

	/**
	 * Destroys the User specified by @param $id. This method is irreversable and should not be called without knowledge of its results.
	 *
	 * @param  int  $id 	ID of the user to mutilate beyond repair (delete).
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}

}
