<?php

namespace Db;

/**
 * 
 * @author Rolando Umana<rolando.umana@gmail.com>
 *
 * Singleton class for Database connection
 */
class Connection
{
	/**
	 * Database connection
	 * @var \PDO
	 */
    protected static $_db;
    
    /**
     * Object should not be instantiated
     */
    private function __construct()
    {
    }
    
    /**
     * Method that returns the instance for the Database connection
     * 
     * @param array $config
     * @return \PDO
     */
    public static function getInstance (array $config) : \PDO
    {
        if (self::$_db === null) {
            $dsn = sprintf(
                "mysql:dbname=%s;host=%s",
                $config['name'],
                $config['host']
            );
            
            self::$_db = new \PDO($dsn, $config['user'], $config['pass']);
            
            //Do not convert numeric values to string when fetching rows
            self::$_db->setAttribute(\PDO::ATTR_EMULATE_PREPARES, false);
            self::$_db->setAttribute(\PDO::ATTR_STRINGIFY_FETCHES, false);
            
        }
        
        return self::$_db;
    }
}
