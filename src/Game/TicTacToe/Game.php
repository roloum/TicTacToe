<?php

namespace Game\TicTacToe;

use \Game\GameAbstract;
use \Game\GameInterface;

class Game extends GameAbstract 
{
	
	public function create (\Game\Player $challenger, array $opponents, string $channel) : GameInterface
	{
		try {
			$this->_db->beginTransaction();
			$this->_create($challenger, $opponents, $channel);
			$this->_db->commit();
			
			return $this;
			
		}
		//If there is an error, rollback the transaction and pass the exception to the caller
		catch (\Exception $e) {
			$this->_db->rollBack();
				
			throw $e;
		}
		
	}
	
	/**
	 * 
	 * {@inheritDoc}
	 * @see \Game\GameAbstract::createDisplay()
	 */
	public function createDisplay (\Game\Player $challenger, array $opponents, string $channel) : string
	{
		$gameExists = false;
		
		try {
			$this->_db->beginTransaction();
			$this->_create($challenger, $opponents, $channel);
		}
		catch (\Game\Exception\ActiveGame $e) {
			$gameExists = true;
		}
		//If there is an error, rollback the transaction and pass the exception to the caller
		catch (\Exception $e) {
			$this->_db->rollBack();
		
			throw $e;
		}
		finally {
			
			$result = sprintf(
				"%s%s",
				($gameExists) ? "There is already an active game on this channel\n" : "",
				$this->_display($channel)
			);
			$this->_db->commit();
		}
	}
	
	protected function _create (\Game\Player $challenger, array $opponents, string $channel) : GameInterface
	{
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
				
				return $this;
				
			}
			else {
				throw new \Game\Exception\ActiveGame();
			}
			
	}
	
	public function display (string $channel) : string
	{
		return $this->_display($channel);
	}
	
	protected function _display (string $channel) : string
	{
		return "Thai";
	}
	
	public function makeMove (\Game\Player $player, \Game\Move $move) : string
	{
		
	}
}

