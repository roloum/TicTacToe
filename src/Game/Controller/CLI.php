<?php

namespace Game\Controller;

class CLI extends Base
{
    /**
     * 
     * {@inheritDoc}
     * @see \Game\Controller\Base::processRequest()
     */
    public function processRequest (array $data) : array
    {
        $isValidCommand = true;
        
        //Request is invalid if it's missing the text parameter
        if ($this->_isValidRequest($data)) {
                
            //Initialize game if request is valid
            $this->_game = new \Game\TicTacToe\Game($this->_db);
                
            $cmd = trim($data["cmd"]);
                
            //If there is no text, we display the board
            if (empty($cmd)) {
                $result = $this->_display($data["channel_id"]);
            }
            //Challenge user
            elseif ($cmd[0] == "@") {
                $challenger = $data["user_name"];
                $opponent = substr($cmd, 1, strlen($cmd)-1);
    
                if ($challenger != $opponent) {
                    
                    if (isset($data['force'])) {
                        $this->_create($challenger, $opponent, $data["channel_id"]);
                        $result = \Game\GameAbstract::MSG_GAME_CREATED;
                    }
                    else {
                        $result = $this->_createDisplay($challenger, $opponent, $data["channel_id"]);
                    }
                }
                else {
                    $result = \Game\GameAbstract::MSG_SELF_CHALLENGE;
                }
            }
            //Make move
            elseif (preg_match("/[123][ABC]/", $cmd)) {
                $result = $this->_makeMove($data["user_name"], $data["channel_id"], $cmd);
            }
            else {
                $isValidCommand = false;
            }
                
        }
        else {
            $isValidCommand = false;
        }
        
        if  (!$isValidCommand) {
            $result = "Usage: php cli.php --user_name \"<username>\" --channel_id \"<channel_id>\" --cmd \"<command>\"\n";
        }
    
        return array("text"=>$result);
    
    }

    /**
     * 
     * {@inheritDoc}
     * @see \Game\Controller\Base::_isValidRequest()
     */
    protected function _isValidRequest(array $data) : bool
    {
        //Validate require fields exist
        if (!isset($data["cmd"])) {
            return false;
        }
        elseif (!isset($data["channel_id"]) || empty($data["channel_id"])) {
            return false;
        }
        elseif (!isset($data["user_name"]) || empty($data["user_name"])) {
            return false;
        }
    
        return true;
    
    }

}
