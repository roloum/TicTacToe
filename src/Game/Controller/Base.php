<?php

namespace Game\Controller;

use Game\Player\{Challenger, Opponent};
use Game\GameInterface;

/**
 * 
 * @author Rolando Umana<rolando.umana@gmail.com>
 *
 * Controller class for the Game
 */
abstract class Base implements ControllerInterface
{
	/**
	 * Database connection
	 * @var \Db\Connection
	 */
	protected $_db;
	
	protected $_game;
	
	/**
	 * 
	 * {@inheritDoc}
	 * @see \Game\Controller\ControllerInterface::processRequest()
	 */
	public abstract function processRequest (array $data) : array;
	
	/**
	 * Instantiates the database connection
	 * 
	 * @throws \Exception
	 */
	public function __construct()
	{
		$configurationFile = realpath(dirname(__FILE__) . '/../../../conf/Settings.php');
		
		if (false === is_readable($configurationFile)) {
			throw new \Exception(sprintf("Configuration file %s is not readable", $configurationFile));
		}
		
		$conf = require $configurationFile;
		
		if (false === isset($conf['db'])) {
			throw new \Exception("Database configuration is not defined");
		}
		$this->_db = \Db\Connection::getInstance($conf['db']);
	}
	
	/**
	 * 
	 * @param array $data
	 * @return bool
	 */
	protected function _isValidRequest(array $data) : bool
	{
		//Validate require fields exist
		if (!isset($data["text"])) {
			return false;
		}
		elseif (!isset($data["channel_id"]) || empty($data["channel_id"])) {
			return false;
		}
		elseif (!isset($data["user_name"]) || empty($data["user_name"])) {
			return false;
		}
	
		//We only receive one command for the moment
		$text = trim(preg_replace("/ +/", " ", $data["text"]));
		if (count(explode(" ", $text)) > 1) {
			return false;
		}
	
		return true;
	
	}

	/**
	 * Creates a TicTacToe game
	 * 
	 * It first validates there is no ongoing game in the channel
	 * Then it creates the game
	 * 
	 * @param string $challenger
	 * @param string $opponent
	 * @param string $channel
	 * @return string
	 */
	public function create(string $challenger, string $opponent, string $channel) : GameInterface
	{
		return $this->_game->create(
			new Challenger($this->_db, $challenger),
			array(
				new Opponent($this->_db, $opponent),
			),
			$channel
		);
	}
	
	/**
	 * Displays the current board
	 * 
	 * @param string $channel
	 * @return string
	 */
	public function display(string $channel) : string
	{
		return $this->_game->display();
	}
	
	/**
	 * 
	 * @param string $player
	 * @param string $channel
	 * @param string $cell
	 * @return string
	 */
	public function makeMove(string $player, string $channel, string $cell) : string
	{
		return "Moo";
	}
	
	/**
	 * Creates the game and immediately displays the Board
	 * 
	 * @param string $challenger
	 * @param string $opponent
	 * @param string $channel
	 * @return string
	 */
	public function createDisplay(string $challenger, string $opponent, string $channel) : string
	{
		return $this->_game->createDisplay(
			new Challenger($this->_db, $challenger),
			array(
				new Opponent($this->_db, $opponent),
			),
			$channel
		);
	}
	
}