<?php

namespace Test\TicTacToe;

use \Game\GameAbstract as GameAbstract;

use \Game\Controller;

use \Test\TicTacToe\Constants as LABEL;

use \PHPUnit_Framework_TestCase as TestCase;

/**
 * 
 * @author Rolando Umana<rolando.umana@gmail.com>
 * 
 * CLI controller test cases. The entry point for the controller is the processRequest
 * method which receives an array with the request options. The processRequest method
 * returns a string, which is what is evaluated on each of the test cases
 *
 */
class CLI extends TestCase {
	
	protected $_controller;

	/**
	 * Constructor instantiates the CLI controller and then calls the parent constructor
	 */
	public function __construct ()
	{
		$this->_controller = new \Game\Controller\CLI();

		return parent::__construct();
	}

	/**
	 * Test movese without pre-existing game
	 */
	public function testMakeMoveWithoutGame ()
	{
		$result = $this->_controller->processRequest(
			array(LABEL::KEY_USER => LABEL::CHALLENGER, LABEL::KEY_CHANNEL => LABEL::CHANNEL, LABEL::KEY_CMD => "1A")
		)["text"];
		
		$this->assertTrue(false !== strpos($result, GameAbstract::MSG_NO_GAME));
	}
	
	/**
	 * Tests the game creation
	 */
	public function testCreateGame ()
	{
		$this->assertTrue(false !== strpos($this->_createGame(LABEL::CHANNEL), GameAbstract::MSG_GAME_CREATED));
	}
	
	/**
	 * @depends testCreateGame
	 * 
	 * Tests winning the game after the creation. This will also free-up the channel
	 * for upcoming test cases
	 */
	public function testWinGame ()
	{
		$this->assertTrue(false !== strpos($this->_winGame(), GameAbstract::MSG_WIN));
	}
	
	/**
	 * @depends testCreateGame
	 * 
	 * Tests game draw.
	 */
	public function testDraw ()
	{
		$this->_createGame(LABEL::CHANNEL);
		$this->assertTrue(false !== strpos($this->_drawGame(), GameAbstract::MSG_DRAW));
	}
	
	/**
	 * Creates a game on a given channel
	 * 
	 * @param string $channel
	 * @return string
	 */
	protected function _createGame (string $channel) : string
	{
		return $this->_controller->processRequest(
			array(LABEL::KEY_USER=>LABEL::CHALLENGER, LABEL::KEY_CHANNEL=>$channel, LABEL::KEY_CMD=>"@".LABEL::OPPONENT)
		)["text"];
	}
	
	/**
	 * Makes all the moves for winning a game and returns the result for the last move
	 * 
	 * @return string
	 */
	protected function _winGame () : string
	{
		$this->_firstMove();
		return $this->_makeWinMoves();
	}
	
	protected function _drawGame () : string
	{
		$this->_firstMove();
		return $this->_makeDrawMoves();
	}
	
	/**
	 * The two moves in this test cases have one common first move.
	 * 
	 * @return string
	 */
	protected function _firstMove () : string
	{
		return $this->_controller->processRequest(
			array(LABEL::KEY_USER=>LABEL::CHALLENGER, LABEL::KEY_CHANNEL=>LABEL::CHANNEL, LABEL::KEY_CMD=>"1A")
		)["text"];
	}
	
	/**
	 * Executes the necessary moves after _firstMove() in order to win the game
	 * 
	 * @return string
	 */
	protected function _makeWinMoves () : string
	{
		return $this->_executeMoves(array(
			array(LABEL::KEY_USER=>LABEL::OPPONENT, LABEL::KEY_CHANNEL=>LABEL::CHANNEL, LABEL::KEY_CMD=>"3C"),
			array(LABEL::KEY_USER=>LABEL::CHALLENGER, LABEL::KEY_CHANNEL=>LABEL::CHANNEL, LABEL::KEY_CMD=>"3A"),
			array(LABEL::KEY_USER=>LABEL::OPPONENT, LABEL::KEY_CHANNEL=>LABEL::CHANNEL, LABEL::KEY_CMD=>"2A"),
			array(LABEL::KEY_USER=>LABEL::CHALLENGER, LABEL::KEY_CHANNEL=>LABEL::CHANNEL, LABEL::KEY_CMD=>"2B"),
			array(LABEL::KEY_USER=>LABEL::OPPONENT, LABEL::KEY_CHANNEL=>LABEL::CHANNEL, LABEL::KEY_CMD=>"1C"),
			array(LABEL::KEY_USER=>LABEL::CHALLENGER, LABEL::KEY_CHANNEL=>LABEL::CHANNEL, LABEL::KEY_CMD=>"1B"),
			array(LABEL::KEY_USER=>LABEL::OPPONENT, LABEL::KEY_CHANNEL=>LABEL::CHANNEL, LABEL::KEY_CMD=>"2C"),
		));
		
	}
	
	/**
	 * Executes the necessary moves after _firstMove() in order to get a draw
	 * 
	 * @return string
	 */
	protected function _makeDrawMoves () : string
	{
		return $this->_executeMoves(array(
			array(LABEL::KEY_USER=>LABEL::OPPONENT, LABEL::KEY_CHANNEL=>LABEL::CHANNEL, LABEL::KEY_CMD=>"3C"),
			array(LABEL::KEY_USER=>LABEL::CHALLENGER, LABEL::KEY_CHANNEL=>LABEL::CHANNEL, LABEL::KEY_CMD=>"3A"),
			array(LABEL::KEY_USER=>LABEL::OPPONENT, LABEL::KEY_CHANNEL=>LABEL::CHANNEL, LABEL::KEY_CMD=>"2A"),
			array(LABEL::KEY_USER=>LABEL::CHALLENGER, LABEL::KEY_CHANNEL=>LABEL::CHANNEL, LABEL::KEY_CMD=>"1C"),
			array(LABEL::KEY_USER=>LABEL::OPPONENT, LABEL::KEY_CHANNEL=>LABEL::CHANNEL, LABEL::KEY_CMD=>"1B"),
			array(LABEL::KEY_USER=>LABEL::CHALLENGER, LABEL::KEY_CHANNEL=>LABEL::CHANNEL, LABEL::KEY_CMD=>"2C"),
			array(LABEL::KEY_USER=>LABEL::OPPONENT, LABEL::KEY_CHANNEL=>LABEL::CHANNEL, LABEL::KEY_CMD=>"2B"),
			array(LABEL::KEY_USER=>LABEL::CHALLENGER, LABEL::KEY_CHANNEL=>LABEL::CHANNEL, LABEL::KEY_CMD=>"3B"),		
		));
	}
	
	
	protected function _executeMoves ($moves)
	{
		$result = "";
		
		foreach ($moves as $move) {
			$result = $this->_controller->processRequest($move);
		}
		
		return $result["text"];
	}
	
	
}
