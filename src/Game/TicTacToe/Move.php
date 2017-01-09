<?php

namespace Game\TicTacToe;

class Move extends \Game\Move
{
	public $x;
	
	public $y;
	
	public $playerId;
	
	public $gameId;
	
	public function __construct (\PDO $db, int $playerId, int $gameId, string $cell)
	{
		//Validate Cell
		if (
			!is_numeric($cell[0]) || 
			intval($cell[0]) < 1 ||
			intval($cell[0]) > 3 ||
			!preg_match("/[ABC]/", $cell[1])
		) {
			throw new \InvalidArgumentException();
		}
		
		//Set class attributes
		$this->x = intval($cell[0]) -1;
		
		switch ($cell[1]) {
			case "A": $this->y = 0; break;
			case "B": $this->y = 1; break;
			case "C": $this->y = 2; break;
		}
		
		$this->_db = $db;
		
		$this->gameId = $gameId;
		
		$this->playerId = $playerId;
		
		$this->_model = new \Game\Model\Move($db);

	}
	
	public function exists () : bool
	{
		return false === empty($this->_model->loadByIndex($this->gameId, $this->x, $this->y));
	}
	
	public function create ()
	{
		$this->_model->create($this->gameId, $this->playerId, $this->x, $this->y);
	}
}