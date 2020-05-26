<?php

namespace Ispitomat;

use GraphAware\Neo4j\OGM\Annotations as OGM;
use GraphAware\Neo4j\OGM\Common\Collection;

/**
 *
 * @OGM\Node(label="Exam")
 */
class Exam
{
	/**
  * @var int
	*
  * @OGM\GraphId()
  */
  protected $id;

  /**
  * @var string
  *
  * @OGM\Property(type="string")
  */
	protected $examID;

	/**
  * @var string
  *
  * @OGM\Property(type="string")
  */
	protected $date;

	/**
  * @var string
  *
  * @OGM\Property(type="string")
  */
	protected $time;

	/**
  * @var string
  *
  * @OGM\Property(type="string")
  */
	protected $duration; // in minutes

	/**
  * @var string
  *
  * @OGM\Property(type="string")
  */
	protected $location;

	/**
  * @var string
  *
  * @OGM\Property(type="string")
  */
	protected $type;

	/**
  * @var string
  *
  * @OGM\Property(type="string")
  */
	protected $schoolYear;

	/**
  * @var float
  *
  * @OGM\Property(type="float")
  */
	protected $maxScore;

  /**
  * @var Student[]|Collection
  *
  * @OGM\Relationship(type="REGISTERED_FOR", direction="INCOMING", collection=true, mappedBy="examsRegisteredFor", targetEntity="Student")
  */
  protected $studentsRegistered;

	/**
  * @var Collection
  *
  * @OGM\Relationship(relationshipEntity="ExamTaken", type="TAKES", direction="INCOMING", collection=true, mappedBy="exam")
  */
  protected $studentsTakenBy;

  /**
  * @var Subject[]|Collection
  *
  * @OGM\Relationship(type="IN", direction="OUTGOING", collection=true, mappedBy="exams", targetEntity="Subject")
  */
  protected $subject;

  public function __construct()
  {
      $this->studentsRegistered = new Collection();
      $this->studentsTakenBy = new Collection();
      $this->subject = new Collection();
  }

	function __get($prop) { return $this->$prop; }
	function __set($prop, $val) { $this->$prop = $val; return $this; }
}

?>
