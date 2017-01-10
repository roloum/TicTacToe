<?php

namespace Game\Board;

/**
 * 
 * @author Rolando Umana<rolando.umana@gmail.com>
 *
 * Board for the TicTacToe game
 */
class TicTacToe implements BoardInterface
{
	/**
	 * Database connection
	 * @var \PDO
	 */
    protected $_db;
    
    /**
     * Move model that contains all moves made in the board
     * @var \Game\Model\Move
     */
    protected $_model;
    
    /**
     * Game id from the Game table
     * @var int
     */
    protected $_gameId;
    
    /**
     * Two dimensional array containing the board moves, used to display the board
     * @var array
     */
    protected $_moves = array();
    
    /**
     * Array used to check if there is a winner or if the game ended
     * @var array
     */
    protected $_boardWinCheck = array();
    
    /**
     * Dimension of the TicTacToe game board 3x3
     * @var integer
     */
    public $dimension = 3;
    
    /**
     * Attribute set to true if the game ended
     * @var bool
     */
    public $full = false;
    
    /**
     * Class constructor, receives the database connection and instantiates the Move model
     * 
     * @param \PDO $db
     * @param int $gameId
     */
    public function __construct(\PDO $db, int $gameId)
    {
        $this->_db = $db;
        
        $this->_model = new \Game\Model\Move($db);
        
        $this->_gameId = $gameId;
    }
    
    /**
     * Loads all the moves from the database into the Board
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
     * 
     * @param array $move
     */
    protected function _fillBoardWinCheck (array $move)
    {
        //The index for the move is: x * <matrix dimension + y
        $this->_boardWinCheck[ $move["x"] * $this->dimension + $move["y"] ] = $move["symbol"];
    }
    
    /**
     * Displays the current board
     * It prepend one row with letters and one column with numbers
     * To help the user provide a cell for the Move 
     * 
     * {@inheritDoc}
     * @see \Game\Board\BoardInterface::display()
     */
    public function display () : string
    {
    	//Create a matrix with the moves from the database
    	//Use spaces for the moves that have not been played
        $board = array();
        for ($i=0; $i<$this->dimension; $i++) {
            for ($j=0; $j<$this->dimension; $j++) {
                $board[$i][$j] = $this->_moves[$i][$j] ?? " ";
            }
            //prepend column with numbers for visual aid for the user
            array_unshift($board[$i], $i+1);
        }
        
        //prepend row with letters
        array_unshift($board, array("A","B","C"));
        
        //Implode each of the rows and then array to generate the text for the board 
        return sprintf("    %s\n", implode("\n  |---+---+---|\n", array_map(function ($row) {
            return sprintf("%s |", implode(" | ", $row));
        }, $board)));        
    }
    
    /**
     * Checks if there's a winner
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
     * Generates the winning conditions dynamically by traversing
     * the matrix in order and rotated 90 degrees
     * 
     * {@inheritDoc}
     * @see \Game\Board\BoardInterface::getWinningConditions()
     */
	public function getWinningConditions() : array
	{
		/**
		 * We use a single array to check if there is a winner, so we need to convert the matrix indexes
		 * to the array index. The formula is index = i*n + j, where n = dimension
		 * 
		 * We don't actually rotate the matrix but only calculate the indexes
		 * rotated i = j
		 * rotated j = abs(i-(n-1)) where n = dimension
		 * 
		 */
		$conditions = $diagonal = $rotatedDiagonal = array();
		
		for ($i=0; $i<$this->dimension; $i++) {
			
			$condition = array();
			$rotatedCondition = array();
			
			for ($j=0; $j<$this->dimension; $j++) {
				$condition[] = $i * $this->dimension + $j;
				$rotatedCondition[] = $j * 3 + abs($i-($this->dimension-1));
			}
			
			$conditions[] = $condition;
			$conditions[] = $rotatedCondition;
			
			$diagonal[] = $i * $this->dimension + $i;
			$rotatedDiagonal[] = $i * $this->dimension + abs($i-($this->dimension-1));
		}
		
		$conditions[] = $diagonal;
		$conditions[] = $rotatedDiagonal;
		
		return $conditions;
	}
    
}
