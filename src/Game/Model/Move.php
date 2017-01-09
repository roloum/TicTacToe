<?php

namespace Game\Model;

class Move extends Base
{
	public function loadMoves (int $gameId) : array
	{
		$mask = sprintf("SELECT * FROM %s WHERE channel_id=? AND status=?", self::TABLE);
		$stmt = $this->_db->prepare($mask);

		$stmt->execute(array($channel, self::STATUS_ACTIVE));

		$result = $stmt->fetchAll(\PDO::FETCH_ASSOC);

		$stmt->closeCursor();

		//Return empty array if Player does not exist
		return $result;
	}

	public function create (string $channel, int $nextPlayerId) : int
	{
		$mask = sprintf("INSERT INTO %s (channel_id, next_player_id, status) VALUES (?, ?, ?)", self::TABLE);
		$stmt = $this->_db->prepare($mask);

		$stmt->execute(array($channel, $nextPlayerId, self::STATUS_ACTIVE));

		$player = $stmt->fetch(\PDO::FETCH_ASSOC);

		$moveId = $this->_db->lastInsertId();
		
		$stmt->closeCursor();
		
		return $moveId;

	}

}