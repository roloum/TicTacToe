<?php

namespace Game\Board;

class TicTacToe implements BoardInterface
{
    protected $_db;
    
    protected $_model;
    
    protected $_gameId;
    
    protected $_moves = array();
    
    protected $_boardWinCheck = array();
    
    public $dimension = 3;
    
    public $full = false;
    
    public function __construct(\PDO $db, int $gameId)
    {
        $this->_db = $db;
        
        $this->_model = new \Game\Model\Move($db);
        
        $this->_gameId = $gameId;
    }
    
    /**
     * 
     * {@inheritDoc}
     * @see \Game\Board\BoardInterface::load()
     */
    public function load ()
    {
        $moves = $this->_model->loadMoves($this->_gameId);
        foreach ($moves as $move) {
            $this->_moves[$move["x"]][$move["y"]] = $move["symbol"];
            
            //Prepares board array for checking game completion
            $this->_fillBoardWinCheck($move);
        }
        
    }
    
    /**
     * This method will fill the boardWinCheck array
     * It represents the matrix in a single array, where we can check the winning conditions
     * @param array $move
     */
    protected function _fillBoardWinCheck (array $move)
    {
        //The index for the move is: x * <matrix dimension + y
        $this->_boardWinCheck[ $move["x"] * $this->dimension + $move["y"] ] = $move["symbol"];
    }
    
    /**
     * 
     * {@inheritDoc}
     * @see \Game\Board\BoardInterface::display()
     */
    public function display () : string
    {
        $board = array();
        for ($i=0; $i<3; $i++) {
            for ($j=0; $j<3; $j++) {
                $board[$i][$j] = $this->_moves[$i][$j] ?? " ";
            }
            //prepend row number
            array_unshift($board[$i], $i+1);
        }
        array_unshift($board, array("A","B","C"));
        
        return sprintf("    %s\n", implode("\n  |---+---+---|\n", array_map(function ($row) {
            return sprintf("%s |", implode(" | ", $row));
        }, $board)));        
    }
    
    /**
     * 
     * {@inheritDoc}
     * @see \Game\Board\BoardInterface::checkWinner()
     */
    public function checkWinner () : bool
    {
        $movesCount = count($this->_boardWinCheck);
        
        //Do not check board if there are not the minimum number of moves to make a winner
        if ($movesCount < $this->dimension * 2 - 1) {
            return false;
        }
        
        if ($movesCount == $this->dimension ** 2) {
            $this->full = true;
        }
        
        foreach ($this->getWinningConditions() as $combination) {
            if (
                isset($this->_boardWinCheck[$combination[0]]) &&
                isset($this->_boardWinCheck[$combination[1]]) &&
                isset($this->_boardWinCheck[$combination[2]]) &&
                $this->_boardWinCheck[$combination[0]] == $this->_boardWinCheck[$combination[1]] &&
                $this->_boardWinCheck[$combination[1]] == $this->_boardWinCheck[$combination[2]]
            ) {
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * 
     * {@inheritDoc}
     * @see \Game\Board\BoardInterface::getWinningConditions()
     */
    public function getWinningConditions() : array
    {
        return array_merge(
            $this->_getWinningRows(),
            $this->_getWinningColumns(),
            $this->_getWinningDiagonals()
        );
    }
    
    /**
     * 
     * @return array
     */
    protected function _getWinningRows () : array
    {
        return array(array(0,1,2), array(3,4,5), array(6,7,8));
    }

    /**
     * 
     * @return array
     */
    protected function _getWinningColumns () : array
    {
        return array(array(0,3,6), array(1,4,7), array(2,5,8));
    }
        
    /**
     *
     * @return array
     */
    protected function _getWinningDiagonals () : array
    {
        return array(array(0,4,8), array(2,4,6));
    }
    
}
