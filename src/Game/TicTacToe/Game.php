<?php

namespace Game\TicTacToe;

use Game\GameAbstract;

class Game extends GameAbstract 
{
	
	public function create (\Game\Player $challenger, array $opponents, string $channel) : Game
	{
		try {
			$this->_db->beginTransaction();
			
			//Check if there is an active game for this channel
			$active = $this->_model->loadActive($channel);
			
			//Create game if it does not exist
			if (empty($active)) {
				
				//Create players if they do not exist yet
				$challenger->createIfNotExist();
				
				$opponent = array_shift($opponents);
				$opponent->createIfNotExist()->playerId;
								
				//Create Game
				$gameId = $this->_model->create($channel, $challenger->playerId);
				
				//Associate Players to Game
				$this->_playerGameModel->create($challenger->playerId, $gameId, $challenger->type, "X");
				$this->_playerGameModel->create($opponent->playerId, $gameId, $opponent->type, "O");
				
			}
			
			$this->_db->commit();
			
			return $this;
		}
		catch (\Exception $e) {
			$this->_db->rollBack();
			
			throw $e;
		}
	}
	
	public function display (string $channel) : string
	{
		return "Thai";
	}
	
	public function makeMove (\Game\Player $player, \Game\Move $move)
	{
		
	}
}

