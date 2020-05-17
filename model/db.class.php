<?php

require_once 'vendor/autoload.php';

use GraphAware\Neo4j\Client\ClientBuilder;

class DB
{
  private static $client = null;

  private function __construct() { }
	private function __clone() { }

  public static function getConnection()
	{
		if (DB::$client === null)
	  {
	    try
	    {
				DB::$client = ClientBuilder::create()
          ->addConnection("default", 'http://neo4j:@localhost:7474')
          ->build();
		   }
		   catch (PDOException $e) { exit ("PDO Error: " . $e->getMessage()); }
	   }
		return DB::$client;
	}
}

 ?>
