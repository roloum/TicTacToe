<?php

namespace Game;

/**
 * 
 * @author Rolando Umana<rolando.umana@gmail.com>
 *
 * All Move classes for the different type of games extend from this class
 */
abstract class Move
{
	/**
	 * Database connection
	 * @var \PDO
	 */
    protected $_db;
    
    /**
     * Move model
     * @var Game\Model\Move
     */
    protected $_model;

    /**
     * 
     * @param \PDO $db
     */
    public abstract function __construct (\PDO $db, int $playerId, int $gameId, string $cell);
}
