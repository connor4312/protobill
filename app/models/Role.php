<?php namespace Model;

use Zizaco\Entrust\EntrustRole;

class Role extends EntrustRole
{
	public static $rules = array(
		'name' => 'required|alphanum'
	);
}