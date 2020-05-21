<?php

namespace Ispitomat;

require_once "model/user.class.php";
use GraphAware\Neo4j\OGM\Common\Collection;

/**
 *
 * @OGM\Node(label="Teacher")
 */
class Teacher extends User
{
	/**
  * @var string
  *
  * @OGM\Property(type="string")
  */
	protected $oib;

	/**
  * @var string
  *
  * @OGM\Property(type="string")
  */
	protected $title;

	/**
  * @var Collection
  *
  * @OGM\Relationship(relationshipEntity="SubjectTeaching", type="TEACHES", direction="OUTGOING", collection=true, mappedBy="teacher")
  */
  protected $subjects;

	public function __construct()
  {
      $this->subjects = new Collection();
  }
}

?>
