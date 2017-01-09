<?php

namespace Game\Board;

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
	 * Checks if the game has finished
	 */
	public function checkGameCompletion ();
}