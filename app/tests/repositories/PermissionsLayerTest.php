<?php

class FakePermissionsClass extends \Repository\PermissionsLayer {
	protected function bar() { return 'foo'; }
}

class PermissionsLayerTest extends TestCase {

	protected function layer() {

		return new FakePermissionsClass();
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
			->setUserContext(\Model\User::find(1));

		$this->assertTrue(! $layer->bar()->isOk());
	}

	public function testUserAllows() {

		$layer = $this->layer()
			->setPermission(array('bar' => 'HasRole:User'))
			->setUserContext(\Model\User::find(1));

		$this->assertTrue($layer->bar() == 'foo');
	}

	public function testUserDenies() {
		$layer = $this->layer()
			->setPermission(array('bar' => 'HasRole:User'));

		$this->assertTrue(! $layer->bar()->isOk());
	}
}