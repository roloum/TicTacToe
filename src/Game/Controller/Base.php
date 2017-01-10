<?php

namespace Game\Controller;

use Game\Player\{Challenger, Opponent};
use Game\GameInterface;

/**
 * 
 * @author Rolando Umana<rolando.umana@gmail.com>
 *
 * Controller class for the Game
 */
abstract class Base implements ControllerInterface
{
    /**
     * Database connection
     * @var \Db\Connection
     */
    protected $_db;
    
    /**
     * Game object
     * @var \GameInterface
     */
    protected $_game;
    
    /**
     * All controllers must implement a processRequest method
     * 
     * {@inheritDoc}
     * @see \Game\Controller\ControllerInterface::processRequest()
     */
    public abstract function processRequest (array $data) : array;
    
    /**
     * Validates the request parameters
     * 
     * @param array $data
     * @return bool
     */
    protected abstract function _isValidRequest (array $data) : bool;
    
    /**
     * Instantiates the database connection
     * 
     * @throws \Exception
     */
    public function __construct()
    {
    	//Requires configuration file
        $configurationFile = realpath(dirname(__FILE__) . '/../../../conf/Settings.php');
        
        if (false === is_readable($configurationFile)) {
            throw new \Exception(sprintf("Configuration file %s is not readable", $configurationFile));
        }
        
        $conf = require $configurationFile;
        
        if (false === isset($conf['db'])) {
            throw new \Exception("Database configuration is not defined");
        }
        
        //Instantiate database connection
        $this->_db = \Db\Connection::getInstance($conf['db']);
    }
    
    /**
     * Creates a game through the Game object
     * 
     * It first validates there is no ongoing game in the channel
     * Then it creates the game
     * 
     * @param string $challenger
     * @param string $opponent
     * @param string $channel
     * @return string
     */
    protected function _create(string $challenger, string $opponent, string $channel) : GameInterface
    {
        return $this->_game->create(
            new Challenger($this->_db, $challenger),
            array(
                new Opponent($this->_db, $opponent),
            ),
            $channel
        );
    }
    
    /**
     * Displays the current board for the game
     * 
     * @param string $channel
     * @return string
     */
    protected function _display(string $channel) : string
    {
        return $this->_game->display($channel);
    }
    
    /**
     * Makes a move in the game object
     * 
     * @param string $player
     * @param string $channel
     * @param string $cell
     * @return string
     */
    protected function _makeMove(string $player, string $channel, string $cell) : string
    {
        return $this->_game->makeMoveDisplay ($player, $channel, $cell);
    }
    
    /**
     * Creates the game and immediately displays the Board
     * 
     * @param string $challenger
     * @param string $opponent
     * @param string $channel
     * @return string
     */
    protected function _createDisplay(string $challenger, string $opponent, string $channel) : string
    {
        return $this->_game->createDisplay(
            new Challenger($this->_db, $challenger),
            array(
                new Opponent($this->_db, $opponent),
            ),
            $channel
        );
    }
    
}
