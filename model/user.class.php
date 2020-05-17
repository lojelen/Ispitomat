<?php

use Doctrine\Common\Collections\ArrayCollection;
use GraphAware\Neo4j\OGM\Annotations as OGM;

/**
 * @OGM\Node(label="User")
 */

class User
{
	protected $userID, $name, $surname, $gender, $dateOfBirth, $address;

	function __construct($userID, $name, $surname, $gender, $dateOfBirth, $address)
	{
		$this->userID = $userID;
		$this->name = $name;
		$this->surname = $surname;
		$this->gender = $gender;
		$this->dateOfBirth = $dateOfBirth;
		$this->address = $address;
	}

	function __get($prop) { return $this->$prop; }
	function __set($prop, $val) { $this->$prop = $val; return $this; }
}

?>
