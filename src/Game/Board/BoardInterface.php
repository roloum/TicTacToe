<?php

namespace Game\Board;

/**
 * 
 * @author Rolando Umana<rolando.umana@gmail.com>
 *
 * Lists the methods that should be implemented by all the Boaard classes 
 */
interface BoardInterface
{
    /**
     * Loads the Moves from the database
     */
    public function load ();
    
    /**
     * Displays the board
     * 
     * @return string
     */
    public function display () : string;
    
    /**
     * Checks if the last play won the game
     * 
     * @return bool
     */
    public function checkWinner () : bool;
    
    /**
     * Generates an array with the winning conditions
     * 
     * @return array
     */
    public function getWinningConditions () : array;
}
