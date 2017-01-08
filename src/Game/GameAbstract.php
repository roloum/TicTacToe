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
	
	protected $_db;
	
	protected $_model;
	
	protected $_playerGameModel;
	
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
	 * @see \Game\GameInterface::makeMove()
	 */
	public abstract function makeMove (Player $player, Move $move) : string;
	
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