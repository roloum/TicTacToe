<?php

namespace Game\Model;

class Game extends Base
{
	const TABLE = "Game";
	
	const STATUS_ACTIVE = "active";
	
	public function loadActive (string $channel) : array
	{
		$mask = sprintf("SELECT * FROM %s WHERE channel_id=? AND status=?", self::TABLE);
		$stmt = $this->_db->prepare($mask);
		
		$stmt->execute(array($channel, self::STATUS_ACTIVE));
		
		$result = $stmt->fetch(\PDO::FETCH_ASSOC);
		
		$stmt->closeCursor();

		//Return empty array if Player does not exist
		return ($result !== false) ? $result : array();
	}
	
	public function create (string $channel, int $nextPlayerId) : int
	{
		$mask = sprintf("INSERT INTO %s (channel_id, next_player_id) VALUES (?, ?)", self::TABLE);
		$stmt = $this->_db->prepare($mask);
		
		$stmt->execute(array($channel, $nextPlayerId));
		
		$player = $stmt->fetch(\PDO::FETCH_ASSOC);
		
		return $this->_db->lastInsertId();
		
	}
	
}