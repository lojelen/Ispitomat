<?php

require_once "vendor/autoload.php";

use GraphAware\Neo4j\OGM\EntityManager;

class DB
{
  private static $em = null;

  private function __construct() { }
	private function __clone() { }

  public static function getConnection()
	{
		if (DB::$em === null)
	  {
	    try
	    {
        DB::$em = EntityManager::create(
          "http://neo4j:@localhost:7474");
		  }
		  catch (Exception $e) { exit ("Error: " . $e->getMessage()); }
	  }
    return DB::$em;
  }
}

 ?>
