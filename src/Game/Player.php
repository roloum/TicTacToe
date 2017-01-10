<?php

namespace Game;

/**
 *
 * @author Rolando Umana<rolando.umana@gmail.com>
 *
 * Player abstract class
 *
 * Includes operatiosn that are common to all players
 */
abstract class Player
{
	/**
	 * Database connection
	 * @var \PDO
	 */
    protected $_db;
    
    /**
     * Player model
     * @var \Game\Model\Player
     */
    protected $_model;

    /**
     * Player id
     * @var int
     */
    public $playerId;
    
    /**
     * Player user name
     * @var string
     */
    public $userName;
    
    /**
     * Player type
     * @var string
     */
    public $type;
    
    /**
     * Sets the user type
     */
    protected abstract function _setType();

    /**
     * Player constructor
     * Sets the userName and has the child class set the user type

     * @param \Db\Connection $db
     * @param string $userName
     */
    public function __construct (\PDO $db, string $userName)
    {
        $this->_db = $db;
        
        $this->userName = $userName;
        $this->_setType();
        
        $this->_model = new \Game\Model\Player($db);
    }
    
    /**
     * Creates the player in the database if it does not exist yet
     * 
     * @return Player
     */
    public function createIfNotExist() : Player
    {
        $user = $this->_model->load($this->userName);
        
        if (empty($user)) {
            $this->playerId = $this->_model->create($this->userName);
        }
        else {
            $this->playerId = $user["player_id"];
        }
        
        return $this;
    }
}
