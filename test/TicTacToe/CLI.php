<?php

namespace Test\TicTacToe;

use \PHPUnit_Framework_TestCase as TestCase;
use \Game\Controller;

class CLI extends TestCase {

	public $permutationObj;

	public function __construct () {
		$this->permutationObj = new \String\IsPermutation();

		return parent::__construct();
	}

	public function testPermutation () {
		$result = $this->permutationObj->isPermutation("eat", "ate");
		$this->assertEquals(true, true);
	}

	public function testSameLengthStrings () {
		$result = $this->permutationObj->isPermutation("flwr", "rose");
		$this->assertEquals(false, $result);
	}

	public function testDifferentLengthStrings () {
		$result = $this->permutationObj->isPermutation("abc", "defg");
		$this->assertEquals(false, $result);
	}
}
