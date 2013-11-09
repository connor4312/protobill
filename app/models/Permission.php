<?php namespace Model;

use Zizaco\Entrust\EntrustPermission;

class Permission extends EntrustPermission
{
	protected $hidden = array('created_at', 'updated_at');
}