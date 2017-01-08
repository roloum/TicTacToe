<?php

namespace Game;

use Game\Player\{Challenger, Opponent};

/**
 * 
 * @author Rolando Umana<rolando.umana@gmail.com>
 *
 * Controller class for the Game
 */
class Controller
{
	/**
	 * Database connection
	 * @var \Db\Connection
	 */
	protected $_db;
	
	protected $_game;
	
	/**
	 * Instantiates the database connection
	 * 
	 * @throws \Exception
	 */
	public function __construct()
	{
		$configurationFile = realpath(dirname(__FILE__) . '/../../conf/Settings.php');
		
		if (false === is_readable($configurationFile)) {
			throw new \Exception(sprintf("Configuration file %s is not readable", $configurationFile));
		}
		
		$conf = require $configurationFile;
		
		if (false === isset($conf['db'])) {
			throw new \Exception("Database configuration is not defined");
		}
		$this->_db = \Db\Connection::getInstance($conf['db']);
	}
	
	public function processRequest (array $data) : array
	{
		//Request is invalid if it's missing the text parameter
		if ($this->_isValidRequest($data)) {
			
			//Initialize game if request is valid
			$this->_game = new \Game\TicTacToe\Game($this->_db);
			
			$text = trim($data["text"]);
			
			//If there is no text, we display the board
			if (empty($text)) {
				$result = $this->display($data["channel_id"]);
			}
			//Challenge user
			elseif ($text[0] == "@") {
				$challenger = $data["user_name"];
				$opponent = substr($text, 1, strlen($text)-1);
				
				if ($challenger != $opponent) {
					$result = $this->create($challenger, $opponent, $data["channel_id"]);
				}
				else {
					$result = "You can not challenge yourself";
				}
			}
			//Make move
			elseif (preg_match("/[ABC][123]/", $text)) {
				$result = $this->makeMove($data["user_name"], $data["channel_id"], $text);
			}
			
		}
		else {
			$result = "Invalid command\nOptions:\n/ttt <@user> to challenge a user\n" .
						"/ttt <cell> to make a play\n/ttt to display the current board";
		}
		
		return array("text" => $result, "response_type"=> "in_channel");
		
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
	public function create(string $challenger, string $opponent, string $channel) : string
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
		return "";
	}
	
	public function makeMove(string $player, string $channel, string $cell) : string
	{
		return "";
	}
}