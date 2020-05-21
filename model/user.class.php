<?php

namespace Ispitomat;

use GraphAware\Neo4j\OGM\Annotations as OGM;

/**
 *
 * @OGM\Node(label="User")
 */
class User
{
	/**
  * @var int
	*
  * @OGM\GraphId()
  */
  protected $neo4jID;

	/**
  * @var string
  *
  * @OGM\Property(type="string")
  */
	protected $userID;

	/**
  * @var string
  *
  * @OGM\Property(type="string")
  */
	protected $name;

	/**
  * @var string
  *
  * @OGM\Property(type="string")
  */
	protected $surname;

	/**
  * @var string
  *
  * @OGM\Property(type="string")
  */
	protected $gender;

	/**
  * @var string
  *
  * @OGM\Property(type="string")
  */
	protected $dateOfBirth;

	/**
  * @var string
  *
  * @OGM\Property(type="string")
  */
	protected $placeOfBirth;

	/**
  * @var string
  *
  * @OGM\Property(type="string")
  */
	protected $address;

	function __get($prop) { return $this->$prop; }
	function __set($prop, $val) { $this->$prop = $val; return $this; }
}

?>
