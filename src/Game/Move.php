<?php

namespace Game;

abstract class Move
{
	protected $_db;
	
	protected $_model;

	/**
	 * 
	 * @param \PDO $db
	 */
	public abstract function __construct (\PDO $db, int $playerId, int $gameId, string $cell);
}