<?php

namespace Ispitomat;

use GraphAware\Neo4j\OGM\Annotations as OGM;
use GraphAware\Neo4j\OGM\Common\Collection;

/**
 *
 * @OGM\Node(label="Subject")
 */
class Subject
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
	protected $subjectID;

	/**
  * @var string
  *
  * @OGM\Property(type="string")
  */
	protected $subjectName;

	/**
  * @var int
  *
  * @OGM\Property(type="int")
  */
	protected $year;

	/**
  * @var string
  *
  * @OGM\Property(type="string")
  */
	protected $semester;

  /**
  * @var boolean
  *
  * @OGM\Property(type="boolean")
  */
  protected $oralExam;

  /**
  * @var Collection
  *
  * @OGM\Relationship(relationshipEntity="StudentSubject", type="ENROLLED_IN", direction="INCOMING", collection=true, mappedBy="subject")
  */
  protected $students;

  /**
  * @var Collection
  *
  * @OGM\Relationship(relationshipEntity="SubjectTeaching", type="TEACHES", direction="INCOMING", collection=true, mappedBy="subject")
  */
  protected $teachers;

  /**
  * @var Exam[]|Collection
  *
  * @OGM\Relationship(type="IN", direction="INCOMING", collection=true, mappedBy="subject", targetEntity="Exam")
  */
  protected $exams;

  public function __construct()
  {
      $this->students = new Collection();
      $this->teacher = new Collection();
      $this->exams = new Collection();
  }

  public static function withArgs($subjectID, $subjectName, $year, $semester) {
      $instance = new self();
      $instance->subjectID = $subjectID;
      $instance->subjectName = $subjectName;
  		$instance->year = $year;
  		$instance->semester = $semester;
      return $instance;
    }

	function __get($prop) { return $this->$prop; }
	function __set($prop, $val) { $this->$prop = $val; return $this; }
}

?>
