<?php

namespace Game\Controller;

class REST extends Base
{
    const CLIENT_TOKEN = "7EBtX0HzVSTyXt5iTJYYwl5X";

    /**
     * 
     * {@inheritDoc}
     * @see \Game\Controller\Base::processRequest()
     */
    public function processRequest (array $data) : array
    {
        if (!$this->_isValidClientToken($data)) {
            return array("text"=>"Invalid token");
        }

        $isValidCommand = true;
        
        //Request is invalid if it's missing the text parameter
        if ($this->_isValidRequest($data)) {
                
            //Initialize game if request is valid
            $this->_game = new \Game\TicTacToe\Game($this->_db);
                
            $text = trim($data["text"]);
                
            //If there is no text, we display the board
            if (empty($text)) {
                $result = $this->_display($data["channel_id"]);
            }
            //Challenge user
            elseif ($text[0] == "@") {
                $challenger = $data["user_name"];
                $opponent = substr($text, 1, strlen($text)-1);
    
                if ($challenger != $opponent) {
                    $result = $this->_createDisplay($challenger, $opponent, $data["channel_id"]);
                }
                else {
                    $result = \Game\GameAbstract::MSG_SELF_CHALLENGE;
                }
            }
            //Make move
            elseif (preg_match("/[123][ABC]/", $text)) {
                $result = $this->_makeMove($data["user_name"], $data["channel_id"], $text);
            }
            else {
                $isValidCommand = false;
            }
                
        }
        else {
            $isValidCommand = false;
        }
        
        if  (!$isValidCommand) {
            $result = sprintf("%s\n%s", $this->_displayUsage());
        }
    
        return array("text" => sprintf("```%s```", $result), "response_type"=> "in_channel");
    
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

    protected function _isValidClientToken (array $data) : bool
    {
        return $data["token"] == self::CLIENT_TOKEN;
    }

    protected function _displayUsage () : string
    {
        return "Invalid command\nOptions:\n\"/ttt @user\" to challenge a user\n" .
            "\"/ttt <cell>\" to make a play (Options are 1A, 1B, 1C, 2A, 2B, 2C, 3A, 3B, 3C)\n" .
            "\"/ttt\" to display the current board";
    }

}
