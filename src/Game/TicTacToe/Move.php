<?php

namespace Game\TicTacToe;

/**
 * 
 * @author Rolando Umana<rolando.umana@gmail.com>
 * 
 * Represents the TicTacToe move in the database
 */
class Move extends \Game\Move
{
    public $x;
    
    public $y;
    
    public $playerId;
    
    public $gameId;
    
    /**
     * Creates a row in the Move table
     * 
     * @param \PDO $db
     * @param int $playerId
     * @param int $gameId
     * @param string $cell
     * @throws \InvalidArgumentException
     */
    public function __construct (\PDO $db, int $playerId, int $gameId, string $cell)
    {
        //Validate TicTacToe Cell
        if (
            !is_numeric($cell[0]) || 
            intval($cell[0]) < 1 ||
            intval($cell[0]) > 3 ||
            !preg_match("/[ABC]/", $cell[1])
        ) {
            throw new \InvalidArgumentException();
        }
        
        //Set cell index
        $this->x = intval($cell[0]) -1;
        
        switch ($cell[1]) {
            case "A": $this->y = 0; break;
            case "B": $this->y = 1; break;
            case "C": $this->y = 2; break;
        }
        
        $this->_db = $db;
        
        $this->gameId = $gameId;
        
        $this->playerId = $playerId;
        
        //Instantiate Move model
        $this->_model = new \Game\Model\Move($db);

    }
    
    /**
     * Verifies if a move exists in the database already for a particular game
     * 
     * @return bool
     */
    public function exists () : bool
    {
        return false === empty($this->_model->loadByIndex($this->gameId, $this->x, $this->y));
    }
    
    /**
     * Creates a row in the Move table
     */
    public function create ()
    {
        $this->_model->create($this->gameId, $this->playerId, $this->x, $this->y);
    }
}
