<?php

namespace Game\Model;

class Move extends Base
{
	const TABLE = "Move";
	
	public function loadMoves (int $gameId) : array
	{
		$mask = sprintf(
			"SELECT m.x, m.y, pg.symbol FROM Move m, Player_Game pg WHERE m.game_id=?" .
				" AND pg.game_id=m.game_id AND pg.player_id=m.player_id",
			self::TABLE
		);
		$stmt = $this->_db->prepare($mask);

		$stmt->execute(array($gameId));

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