<?php

namespace Game\Model;

/**
 * 
 * @author Rolando Umana<rolando.umana@gmail.com>
 * 
 * Model that associates a player with a game
 */
class PlayerGame extends Base
{
    
	/**
	 * Creates one row in the Player_Game table per player in $players array
	 * 
	 * @param array $players
	 */
    public function create (array $players)
    {
        $mask = sprintf("INSERT INTO %s (player_id, game_id, role, symbol) VALUES (?, ?, ?, ?)", Tables::PLAYER_GAME);
        
        $stmt = $this->_db->prepare($mask);
        
        foreach ($players as $player) {
            $stmt->execute($player);
        }
        $stmt->fetch(\PDO::FETCH_ASSOC);
        
        $stmt->closeCursor();
    }
    
}
