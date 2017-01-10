<?php

namespace Game\TicTacToe;

use \Game\GameAbstract;
use \Game\GameInterface;

/**
 * 
 * @author Rolando Umana<rolando.umana@gmail.com>
 *
 * TicTacToe Game
 */
class Game extends GameAbstract 
{
    
    public function create (\Game\Player $challenger, array $opponents, string $channel) : GameInterface
    {
        try {
            $this->_db->beginTransaction();
            if (!$this->_create($challenger, $opponents, $channel)) {
                throw new \Game\Exception\ActiveGame(self::MSG_ACTIVE_GAME);
            }
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
        try {
            $this->_db->beginTransaction();

            $result = sprintf(
                "%s\n%s",
                ($this->_create($challenger, $opponents, $channel)) ? self::MSG_GAME_CREATED : self::MSG_ACTIVE_GAME,
                $this->_display($channel)
            );
            
            $this->_db->commit();
            
            return $result;
        }
        //If there is an error, rollback the transaction and pass the exception to the caller
        catch (\Exception $e) {
            $this->_db->rollBack();
        
            throw $e;
        }
    }
    
    protected function _load (string $channel) : bool
    {
        $activeGame = $this->_model->loadActive($channel);
        if (empty($activeGame)) {
            return false;
        }
        
        $this->id = $activeGame["game_id"];
        $this->nextPlayerId = $activeGame["next_player_id"];
        $this->nextPlayer = $activeGame["user_name"];
        
        $this->board = new \Game\Board\TicTacToe($this->_db, $this->id);
        $this->board->load();
        
        return true;
        
    }
    
    protected function _create (\Game\Player $challenger, array $opponents, string $channel) : bool
    {
            //Create game if it does not exist
            if (!$this->_load($channel)) {
                
                //Create players if they do not exist yet
                $challenger->createIfNotExist();
                
                $opponent = array_shift($opponents);
                $opponent->createIfNotExist();
                                
                //Create Game
                $this->id = $this->_model->create($channel, $challenger->playerId);
                
                //Associate Players to Game
                $this->_playerGameModel->create(array(
                    array($opponent->playerId, $this->id, $opponent->type, "O"),
                    array($challenger->playerId, $this->id, $challenger->type, "X"),
                ));
                
                //Load Game after it has been created
                $this->_load($channel);
                
                return true;
                
            }
            else {
                return false;
            }
            
    }
    
    public function display (string $channel) : string
    {
        try {
            $this->_db->beginTransaction();
        
            $result = $this->_load($channel) ? $this->_display() : self::MSG_NO_GAME;
            
            $this->_db->commit();
                
            return $result;
        }
        //If there is an error, rollback the transaction and pass the exception to the caller
        catch (\Exception $e) {
            $this->_db->rollBack();
        
            throw $e;
        }
    }
    
    protected function _display () : string
    {
        return sprintf("%s\n%s", $this->board->display(), $this->_displayNextPlayer());
    }
    
    protected function _displayNextPlayer () : string
    {
        return $this->_gameEnded ? "" : sprintf("Next player is: @%s", $this->nextPlayer);
    }
    
    public function makeMoveDisplay (string $player, string $channel, string $cell) : string
    {
        $result = "";
        
        try {
            $this->_db->beginTransaction();
            
            if (!$this->_load($channel)) {
                $result = sprintf("%s\n", self::MSG_NO_GAME);
            }
            else {
                if ($this->nextPlayer != $player) {
                    $result = sprintf("%s\n", self::MSG_UNATHORIZED_PLAYER);
                }
                else {
                    
                    //Create move object
                    $move = new \Game\TicTacToe\Move($this->_db, $this->nextPlayerId, $this->id, $cell);
                    
                    if ($move->exists()) {
                        $result = sprintf("%s\n", self::MSG_MOVE_ALREADY_PLAYED);
                    }
                    else {
                        //Make move
                        $move->create();
                        
                        //Update next player
                        $this->_model->alternateNextPlayer($this->id);
                        
                        //Reload Game object
                        $this->_load($channel);
                        
                        if ($this->board->checkWinner()) {
                            $result = sprintf("@%s %s\n", $player, self::MSG_WIN);
                            $this->_model->updateStatusWin($this->id);
                            $this->_gameEnded = true;
                        }
                        elseif ($this->board->full) {
                            $result = sprintf("%s\n", self::MSG_DRAW);
                            $this->_model->updateStatusDraw($this->id);
                            $this->_gameEnded = true;
                        }
                    }
                    
                }
                
                //Display new board
                $result .= $this->_display();
            }
                
            $this->_db->commit();
                
            return $result;
        }
        //If there is an error, rollback the transaction and pass the exception to the caller
        catch (\Exception $e) {
            $this->_db->rollBack();
        
            throw $e;
        }
    }
    
}

