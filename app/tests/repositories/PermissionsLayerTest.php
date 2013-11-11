<?php

class FakePermissionsClass extends \Repository\PermissionsLayer {
	protected function bar() { return 'foo'; }
}

class PermissionsLayerTest extends TestCase {

	protected $generated = false;
	protected $user = null;

	protected function layer() {

		return new FakePermissionsClass();
	}

	protected function user() {

		return \Model\User::find(1);
	}

	protected function roledUser() {

		if ($this->generated) {
			return $this->user();
		}

		$admin = new \Model\Role;
		$admin->name = 'Demorole';
		$admin->save();

		$managePerms = new \Model\Permission;
		$managePerms->name = 'demo_permission';
		$managePerms->display_name = 'Demo Permission';
		$managePerms->save();

		$admin->perms()->sync(array($managePerms->id));

		$user = $this->user();
		$user->attachRole($admin);

		$this->generated = true;

		return $this->user();
	}

	public function testBlocksByDefault() {

		$this->assertTrue(! $this->layer()->bar()->isOk());
	}

	public function testGuestAllows() {

		$layer = $this->layer()->setPermission(array('bar' => 'HasRole:Guest'));

		$this->assertTrue($layer->bar() == 'foo');
	}

	public function testGuestDenies() {

		$layer = $this->layer()
			->setPermission(array('bar' => 'HasRole:Guest'))
			->setUserContext($this->user());

		$this->assertTrue(! $layer->bar()->isOk());
	}

	public function testUserAllows() {

		$layer = $this->layer()
			->setPermission(array('bar' => 'HasRole:User'))
			->setUserContext($this->user());

		$this->assertTrue($layer->bar() == 'foo');
	}

	public function testUserDenies() {

		$layer = $this->layer()
			->setPermission(array('bar' => 'HasRole:User'));

		$this->assertTrue(! $layer->bar()->isOk());
	}

	public function testEntrustRoleAllows() {

		$layer = $this->layer()
			->setPermission(array('bar' => 'HasRole:Demorole'))
			->setUserContext($this->roledUser());

		$this->assertTrue($layer->bar() == 'foo');
	}

	public function testEntrustRoleDenies() {

		$layer = $this->layer()
			->setPermission(array('bar' => 'HasRole:Foo'))
			->setUserContext($this->roledUser());

		$this->assertTrue(! $layer->bar()->isOk());
	}

	public function testEntrustCanAllows() {

		$layer = $this->layer()
			->setPermission(array('bar' => 'Can:demo_permission'))
			->setUserContext($this->roledUser());

		$this->assertTrue($layer->bar() == 'foo');
	}

	public function testEntrustCanDenies() {

		$layer = $this->layer()
			->setPermission(array('bar' => 'Can:manage'))
			->setUserContext($this->roledUser());

		$this->assertTrue(! $layer->bar()->isOk());
	}
}