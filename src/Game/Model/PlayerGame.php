<?php

namespace Game\Model;

class PlayerGame extends Base
{
	const TABLE = "Player_Game";
	
	public function create (array $players)
	{
		$mask = sprintf("INSERT INTO %s (player_id, game_id, role, symbol) VALUES (?, ?, ?, ?)", self::TABLE);
		
		$stmt = $this->_db->prepare($mask);
		
		foreach ($players as $player) {
			$stmt->execute($player);
		}
		$stmt->fetch(\PDO::FETCH_ASSOC);
		
		$stmt->closeCursor();
	}
	
}
