<?php

namespace Game\Controller;

class REST extends Base
{
	/**
	 * 
	 * {@inheritDoc}
	 * @see \Game\Controller\Base::processRequest()
	 */
	public function processRequest (array $data) : array
	{
		//Request is invalid if it's missing the text parameter
		if ($this->_isValidRequest($data)) {
				
			//Initialize game if request is valid
			$this->_game = new \Game\TicTacToe\Game($this->_db);
				
			$text = trim($data["text"]);
				
			//If there is no text, we display the board
			if (empty($text)) {
				$result = $this->display($data["channel_id"]);
			}
			//Challenge user
			elseif ($text[0] == "@") {
				$challenger = $data["user_name"];
				$opponent = substr($text, 1, strlen($text)-1);
	
				if ($challenger != $opponent) {
					$result = $this->createDisplay($challenger, $opponent, $data["channel_id"]);
				}
				else {
					$result = "You can not challenge yourself";
				}
			}
			//Make move
			elseif (preg_match("/[ABC][123]/", $text)) {
				$result = $this->makeMove($data["user_name"], $data["channel_id"], $text);
			}
				
		}
		else {
			$result = "Invalid command\nOptions:\n/ttt <@user> to challenge a user\n" .
					"/ttt <cell> to make a play\n/ttt to display the current board";
		}
	
		return array("text" => $result, "response_type"=> "in_channel");
	
	}

	/**
	 * 
	 * {@inheritDoc}
	 * @see \Game\Controller\Base::_isValidRequest()
	 */
	protected function _isValidRequest(array $data) : bool
	{
		//Validate require fields exist
		if (!isset($data["text"])) {
			return false;
		}
		elseif (!isset($data["channel_id"]) || empty($data["channel_id"])) {
			return false;
		}
		elseif (!isset($data["user_name"]) || empty($data["user_name"])) {
			return false;
		}
	
		//We only receive one command for the moment
		$text = trim(preg_replace("/ +/", " ", $data["text"]));
		if (count(explode(" ", $text)) > 1) {
			return false;
		}
	
		return true;
	
	}

}