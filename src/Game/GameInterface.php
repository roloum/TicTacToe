<?php

namespace Game;

use Game\{Player,Move};

/**
 * 
 * @author Rolando Umana<rolando.umana@gmail.com>
 *
 * Methods that should be implemented in all games
 */
interface GameInterface
{
	/**
	 * Creates the game
	 * 
	 * Returns object of type GameInterface. This method is used for test cases.
	 * 
	 * @param unknown $challenger
	 * @param unknown $opponents
	 * @param unknown $channel
	 */
	public function create (Player $challenger, array $opponents, string $channel) : GameInterface;
	
	/**
	 * Creates the game and immediately displays the board
	 * 
	 * @param Player $challenger
	 * @param array $opponents
	 * @param string $channel
	 * @return string
	 */
	public function createDisplay (Player $challenger, array $opponents, string $channel) : string;
	
	/**
	 * Displays the game board
	 * 
	 * @param string $channel
	 * @return string
	 */
	public function display (string $channel) : string;
	
	/**
	 * Allows a player to make a move and then displays the board
	 * 
	 * @param string $player
	 * @param string $channel
	 * @param string $cell
	 * @return string
	 */
	public function makeMoveDisplay (string $player, string $channel, string $cell) : string;
}