<?php

namespace Game\Model;

class Base
{

	protected $_db;

	public function __construct(\PDO $db)
	{
		$this->_db = $db;
	}
}