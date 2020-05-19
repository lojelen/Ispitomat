<?php

use Doctrine\Common\Collections\ArrayCollection;
use GraphAware\Neo4j\OGM\Annotations as OGM;

/**
 * @OGM\Node(label="Subject")
 */

class Subject
{
	protected $subjectID, $subjectName, $year, $semester;

	function __construct($subjectID, $subjectName, $year, $semester)
	{
		$this->subjectID = $subjectID;
		$this->subjectName = $subjectName;
		$this->year = $year;
		$this->semester = $semester;
	}

	function __get($prop) { return $this->$prop; }
	function __set($prop, $val) { $this->$prop = $val; return $this; }
}

?>
