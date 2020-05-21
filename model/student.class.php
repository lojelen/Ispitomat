<?php

namespace Ispitomat;

require_once "model/user.class.php";
use GraphAware\Neo4j\OGM\Annotations as OGM;
use GraphAware\Neo4j\OGM\Common\Collection;

/**
 *
 * @OGM\Node(label="Student")
 */
class Student extends User
{
	/**
  * @var string
  *
  * @OGM\Property(type="string")
  */
	protected $jmbag;

	/**
  * @var Collection
  *
  * @OGM\Relationship(relationshipEntity="StudentSubject", type="ENROLLED_IN", direction="OUTGOING", collection=true, mappedBy="student")
  */
  protected $subjects;

	/**
  * @var Exam[]|Collection
  *
  * @OGM\Relationship(type="REGISTERED_FOR", direction="OUTGOING", collection=true, mappedBy="studentsRegistered", targetEntity="Exam")
  */
  protected $examsRegisteredFor;

	/**
  * @var Collection
  *
  * @OGM\Relationship(relationshipEntity="ExamTaken", type="TAKES", direction="OUTGOING", collection=true, mappedBy="student")
  */
  protected $examsTaken;

  public function __construct()
  {
      $this->subjects = new Collection();
			$this->examsRegisteredFor = new Collection();
			$this->examsTaken = new Collection();
  }
}

?>
