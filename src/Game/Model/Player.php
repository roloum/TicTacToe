<?php

namespace Game\Model;

class Player extends Base
{
	const TABLE = "Player";
	
	public function load (string $userName) : array
	{
		$mask = sprintf("SELECT * FROM %s WHERE user_name=?", self::TABLE);
		$stmt = $this->_db->prepare($mask);
		
		$stmt->execute(array($userName));
		
		$result = $stmt->fetch(\PDO::FETCH_ASSOC);
		
		$stmt->closeCursor();

		//Return empty array if Player does not exist
		return ($result !== false) ? $result : array();
	}
	
	public function create (string $userName) : int
	{
		$mask = sprintf("INSERT INTO %s (user_name) VALUES (?)", self::TABLE);
		$stmt = $this->_db->prepare($mask);
		
		$stmt->execute(array($userName));
		
		$player = $stmt->fetch(\PDO::FETCH_ASSOC);
		
		return $this->_db->lastInsertId();
	}
	
}