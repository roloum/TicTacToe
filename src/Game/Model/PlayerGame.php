<?php

namespace Game\Model;

class PlayerGame extends Base
{
	const TABLE = "Player_Game";
	
	public function create (int $playerId, int $gameId, string $role, string $letter)
	{
		$mask = sprintf("INSERT INTO %s VALUES (?, ?, ?, ?)", self::TABLE);
		$stmt = $this->_db->prepare($mask);
		
		$stmt->execute(array($playerId, $gameId, $role, $letter));
		$stmt->closeCursor();
	}
	
}