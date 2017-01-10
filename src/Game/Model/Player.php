<?php

namespace Game\Model;

/**
 * 
 * @author Rolando Umana<rolando.umana@gmail.com>
 * 
 * Database operations for the Player table
 */
class Player extends Base
{
    
	/**
	 * Loads a row from the player table by user_name
	 * 
	 * @param string $userName
	 * @return array
	 */
    public function load (string $userName) : array
    {
        $mask = sprintf("SELECT * FROM %s WHERE user_name=?", Tables::PLAYER);
        $stmt = $this->_db->prepare($mask);
        
        $stmt->execute(array($userName));
        
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        
        $stmt->closeCursor();

        //Return empty array if Player does not exist
        return ($result !== false) ? $result : array();
    }
    
    /**
     * Creates a row in the player table
     * 
     * @param string $userName
     * @return int
     */
    public function create (string $userName) : int
    {
        $mask = sprintf("INSERT INTO %s (user_name) VALUES (?)", Tables::PLAYER);
        $stmt = $this->_db->prepare($mask);
        
        $stmt->execute(array($userName));
        
        $player = $stmt->fetch(\PDO::FETCH_ASSOC);
        
        return $this->_db->lastInsertId();
    }
    
}
