<?php

namespace Game\Model;

class Game extends Base
{
	const STATUS_ACTIVE = "active";
	const STATUS_DRAW = "draw";
	const STATUS_WIN = "win";
	
	/**
	 * Loads the current active game for a channel
	 * 
	 * @param string $channel
	 * @return array
	 */
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
	
	/**
	 * Creates a game for a given channel and sets the next player id
	 * 
	 * @param string $channel
	 * @param int $nextPlayerId
	 * @return int
	 */
	public function create (string $channel, int $nextPlayerId) : int
	{
		$mask = sprintf("INSERT INTO %s (channel_id, next_player_id, status) VALUES (?, ?, ?)", Tables::GAME);
		$stmt = $this->_db->prepare($mask);
		
		$stmt->execute(array($channel, $nextPlayerId, self::STATUS_ACTIVE));
		
		$player = $stmt->fetch(\PDO::FETCH_ASSOC);
		
		return $this->_db->lastInsertId();
		
	}
	
	/**
	 * Switches next player on the game after each move
	 * 
	 * @param int $gameId
	 */
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
	
	/**
	 * Updates game status to win
	 * 
	 * @param int $gameId
	 */
	public function updateStatusWin (int $gameId)
	{
		$this->_updateStatus($gameId, self::STATUS_WIN);
	}
	
	/**
	 * Updates game status to Draw
	 * 
	 * @param int $gameId
	 */
	public function updateStatusDraw (int $gameId)
	{
		$this->_updateStatus($gameId, self::STATUS_DRAW);
	}
	
	/**
	 * Updates game status
	 * 
	 * @param int $gameId
	 * @param string $status
	 */
	private function _updateStatus (int $gameId, string $status)
	{
		$mask = sprintf("UPDATE %s SET status = ? WHERE game_id=?", Tables::GAME);
		$stmt = $this->_db->prepare($mask);
		
		$stmt->execute(array($status, $gameId));
		
		$stmt->closeCursor();
	}
	
}