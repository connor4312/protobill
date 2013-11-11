<?php

use Repository\EloquentRoleRepository;

class RoleTest extends TestCase {

	protected function repo() {
		return new EloquentRoleRepository;
	}

	protected function createrole() {

		$role1 = new \Model\Role;
		$role1->name = 'Demorole';
		$role1->save();

		$role2 = new \Model\Role;
		$role2->name = 'Demorole2';
		$role2->save();

		$perm1 = new \Model\Permission;
		$perm1->name = 'demo_permission';
		$perm1->display_name = 'Demo Permission';
		$perm1->save();

		$perm2 = new \Model\Permission;
		$perm2->name = 'demo_permission2';
		$perm2->display_name = 'Demo Permission 2';
		$perm2->save();

		$perm3 = new \Model\Permission;
		$perm3->name = 'demo_permission3';
		$perm3->display_name = 'Demo Permission 3';
		$perm3->save();

		$role1->perms()->sync(array($perm1->id, $perm2->id));
	}

	public function testShowsAll() {
		$this->createrole();
		$data = $this->repo()->all();

		$this->assertTrue(is_array($data));
		$this->assertEquals(count($data), 2);
	}

	public function testShowsSingle() {
		$this->createrole();
		$data = $this->repo()->show(1);

		$this->assertArrayHasKey('id', $data);
	}

	public function testShowsPermissions() {
		$this->createrole();
		$data = $this->repo()->all();

		$this->assertEquals(count($data[0]['permissions']), 2);
		$this->assertEquals(count($data[1]['permissions']), 0);
	}

	public function testDeletes() {
		$this->createrole();
		$this->repo()->delete(1);
		$data = $this->repo()->all();

		$this->assertEquals(count($data), 1);
	}

	public function testEdits() {
		$this->createrole();
		$data = $this->repo()->edit(1, array('name' => 'Foo', 'permissions' => array(1, 3)));

		$this->assertEquals($data['name'], 'Foo');

		$this->assertTrue(in_array(1, $data['permissions']));
		$this->assertTrue(in_array(3, $data['permissions']));
		
		$this->assertFalse(in_array(2, $data['permissions']));
	}
}