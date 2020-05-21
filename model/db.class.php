<?php

require_once 'vendor/autoload.php';

use GraphAware\Neo4j\Client\ClientBuilder;
use GraphAware\Neo4j\OGM\EntityManager;

class DB
{
  private static $client = null;
  private static $em = null;

  private function __construct() { }
	private function __clone() { }

  public static function getConnection()
	{
		if (DB::$em === null)
	  {
	    try
	    {
        DB::$em = EntityManager::create("http://neo4j:@localhost:7474");
				/*DB::$client = ClientBuilder::create()
          ->addConnection("default", 'http://neo4j:@localhost:7474')
          ->build();*/
		   }
		   catch (Exception $e) { exit ("Error: " . $e->getMessage()); }
		 //return DB::$client;
     return DB::$em;
	  }
  }

  public static function getClient()
  {
    if (DB::$client === null)
	  {
	    try
	    {
				DB::$client = ClientBuilder::create()
          ->addConnection("default", 'http://neo4j:@localhost:7474')
          ->build();
		   }
		   catch (Exception $e) { exit ("Error: " . $e->getMessage()); }
		 return DB::$client;
	  }
  }
}

 ?>
