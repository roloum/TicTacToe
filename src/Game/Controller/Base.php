<?php

namespace Game\Controller;

use Game\Player\{Challenger, Opponent};

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
	 * 
	 * @param array $data
	 * @return bool
	 */
	protected abstract function _isValidRequest(array $data) : bool;
	
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
	public function create(string $challenger, string $opponent, string $channel) : \Game\TicTacToe\Game
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
	
	public function makeMove(string $player, string $channel, string $cell) : string
	{
		return "";
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
		return $this->create($challenger, $opponent, $channel)->display($channel);
	}
	
}