<?php

namespace Game\Model;

/**
 * 
 * @author Rolando Umana<rolando.umana@gmail.com>
 *
 * Retrieves Slack tokens
 */
class SlackToken extends Base
{
	/**
	 * Retrieves the Slack Tokens
	 * 
	 * @return array
	 */
    public function load () : array
    {
        $stmt = $this->_db->prepare("SELECT * FROM Slack_Token");

        $stmt->execute();

        $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $stmt->closeCursor();

        return $result;
    }

}
