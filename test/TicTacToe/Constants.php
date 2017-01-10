<?php

namespace Test\TicTacToe;

/**
 * 
 * @author Rolando Umana<rolando.umana@gmail.com>
 *
 * Class contains constants with all the keys and values
 * to execute the test cases
 */
class Constants
{
	/**
	 * Main channel used for test cases
	 * @var string
	 */
	const CHANNEL = "C2147483705";
	
	/**
	 * Additional channel will allow to have two active games in parallel
	 * @var string
	 */
	const ANOTHER_CHANNEL = "C2147483704";
	
	/**
	 * Challenger player
	 * @var string
	 */
	const CHALLENGER = "roloum";
	
	/**
	 * Opponent player
	 * @var string
	 */
	const OPPONENT = "guesthotmail";
	
	/**
	 * User name parameter key
	 * @var string
	 */
	const KEY_USER = "user_name";
	
	/**
	 * Channel parameter key
	 * @var string
	 */
	const KEY_CHANNEL = "channel_id";
	
	/**
	 * Command parameter key
	 * @var string
	 */
	const KEY_CMD = "cmd";
	
	/**
	 * Forced parameter key
	 * @var string
	 */
	const KEY_FORCE = "force";
	
	
}