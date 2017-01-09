<?php

namespace Game\Board;

class TicTacToe implements BoardInterface
{
	protected $_db;
	
	protected $_model;
	
	protected $_gameId;
	
	protected $_moves = array();
	
	protected $_board;
	
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
		}
		
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
	 * @see \Game\Board\BoardInterface::checkGameCompletion()
	 */
	public function checkGameCompletion ()
	{
		
	}
}