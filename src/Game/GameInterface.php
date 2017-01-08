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
	 * @param array $players
	 * @param string $channel
	 */
	public function create (Player $challenger, array $opponents, string $channel);
	
	/**
	 * Displays the game board
	 * 
	 * @param string $channel
	 */
	public function display (string $channel);
	
	/**
	 * Allows a player to make a move
	 * 
	 * @param Player $player
	 * @param Move $move
	 */
	public function makeMove (Player $player, Move $move);
}