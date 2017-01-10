<?php

namespace Game\Model;

class Move extends Base
{
    public function loadMoves (int $gameId) : array
    {
        $mask = sprintf(
            "SELECT m.x, m.y, pg.symbol FROM %s m, %s pg WHERE m.game_id=?" .
                " AND pg.game_id=m.game_id AND pg.player_id=m.player_id",
            Tables::MOVE,
            Tables::PLAYER_GAME
        );
        $stmt = $this->_db->prepare($mask);

        $stmt->execute(array($gameId));

        $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $stmt->closeCursor();

        //Return empty array if Player does not exist
        return $result;
    }

    public function loadByIndex (int $gameId, int $x, int $y) : array
    {
        $mask = sprintf(
                "SELECT move_id FROM %s WHERE game_id=? and x=? and y=?",
                Tables::MOVE
        );
        $stmt = $this->_db->prepare($mask);
        
        $stmt->execute(array($gameId, $x, $y));
        
        $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        
        $stmt->closeCursor();
        
        //Return empty array if Player does not exist
        return $result;
    }

    public function create (int $gameId, int $playerId, int $x, int $y) : int
    {
        $mask = sprintf("INSERT INTO %s (player_id, game_id, x, y) VALUES (?, ?, ?, ?)", Tables::MOVE);
        $stmt = $this->_db->prepare($mask);
        
        $stmt->execute(array($playerId, $gameId, $x, $y));
        
        $player = $stmt->fetch(\PDO::FETCH_ASSOC);
        
        return $this->_db->lastInsertId();
        
    }
    
}
