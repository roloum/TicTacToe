<?php

namespace Game\Model;

class Game extends Base
{
	const STATUS_ACTIVE = "active";
	
	public function loadActive (string $channel) : array
	{
		$mask = sprintf(
			"SELECT g.*, p.user_name FROM %s g, %s pg, %s p WHERE g.channel_id=? AND g.status=?" .
				" AND pg.player_id=g.next_player_id AND pg.game_id=g.game_id" .
				" AND p.player_id=pg.player_id",
			Tables::GAME,
			Tables::PLAYER_GAME,
			Tables::PLAYER
		);
		$stmt = $this->_db->prepare($mask);
		
		$stmt->execute(array($channel, self::STATUS_ACTIVE));
		
		$result = $stmt->fetch(\PDO::FETCH_ASSOC);
		
		$stmt->closeCursor();

		//Return empty array if Player does not exist
		return ($result !== false) ? $result : array();
	}
	
	public function create (string $channel, int $nextPlayerId) : int
	{
		$mask = sprintf("INSERT INTO %s (channel_id, next_player_id, status) VALUES (?, ?, ?)", Tables::GAME);
		$stmt = $this->_db->prepare($mask);
		
		$stmt->execute(array($channel, $nextPlayerId, self::STATUS_ACTIVE));
		
		$player = $stmt->fetch(\PDO::FETCH_ASSOC);
		
		return $this->_db->lastInsertId();
		
	}
	
	public function alternateNextPlayer (int $gameId)
	{
		$mask = sprintf(
			"UPDATE %s g, %s pg SET g.next_player_id=pg.player_id" .
				" WHERE g.game_id=? AND pg.player_id <> g.next_player_id and pg.game_id=g.game_id;",
			Tables::GAME,
			Tables::PLAYER_GAME
		);
		$stmt = $this->_db->prepare($mask);
		
		$stmt->execute(array($gameId));
		
		$stmt->closeCursor();
	}
	
}