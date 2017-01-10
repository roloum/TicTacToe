<?php

namespace Game;

/**
 * 
 * @author Rolando Umana<rolando.umana@gmail.com>
 *
 * GameAbstract class contains 3 methods 
 */
abstract class GameAbstract implements GameInterface
{
	
	const MSG_GAME_CREATED = "Game successfully created.";
	
	const MSG_NO_GAME = "There is no active game on this channel.";
	
	const MSG_ACTIVE_GAME = "There is already an active game on this channel.";
	
	const MSG_SELF_CHALLENGE = "You can not challenge yourself.";
	
	const MSG_UNATHORIZED_PLAYER = "Unathorized player.";
	
	const MSG_MOVE_ALREADY_PLAYED = "Move was already played.";
	
	const MSG_DRAW = "It is a draw.";
	
	const MSG_WIN = "won the game!";
	
	protected $_db;
	
	protected $_model;
	
	protected $_playerGameModel;
	
	protected $_gameEnded = false;

	public $id;
	
	public $nextPlayerId;
	public $nextPlayer;
	
	public $board;
		
	/**
	 * 
	 * {@inheritDoc}
	 * @see \Game\GameInterface::create()
	 */
	public abstract function create (\Game\Player $challenger, array $opponents, string $channel) : GameInterface;
	
	/**
	 * 
	 * {@inheritDoc}
	 * @see \Game\GameInterface::createDisplay()
	 */
	public abstract function createDisplay (Player $challenger, array $opponents, string $channel) : string;
	
	/**
	 * 
	 * {@inheritDoc}
	 * @see \Game\GameInterface::display()
	 */
	public abstract function display (string $channel) : string;
		
	/**
	 * 
	 * {@inheritDoc}
	 * @see \Game\GameInterface::makeMoveDisplay()
	 */
	public abstract function makeMoveDisplay(string $player, string $channel, string $cell) : string;
		
	/**
	 * 
	 * @param \Db\Connection $db
	 */
	public function __construct (\PDO $db)
	{
		$this->_db = $db;
		
		$this->_model = new \Game\Model\Game($db);
		
		$this->_playerGameModel = new \Game\Model\PlayerGame($db);
		
	}
	
}