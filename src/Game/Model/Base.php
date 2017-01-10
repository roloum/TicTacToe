<?php

namespace Game\Model;

/**
 * 
 * @author Rolando Umana<rolando.umana@gmail.com>
 *
 * Base model. All other models in the application extend from this class
 */
class Base
{

	/**
	 * Database connection
	 * @var \PDO
	 */
    protected $_db;

    /**
     * Class constructor sets the database connection
     * 
     * @param \PDO $db
     */
    public function __construct(\PDO $db)
    {
        $this->_db = $db;
    }
}
