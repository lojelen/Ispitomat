<?php

namespace Ispitomat;

use GraphAware\Neo4j\OGM\Annotations as OGM;

/**
 *
 * @OGM\RelationshipEntity(type="ENROLLED_IN")
 */
class StudentSubject
{
    /**
     * @var int
     *
     * @OGM\GraphId()
     */
    protected $neo4jID;

    /**
     * @var Student
     *
     * @OGM\StartNode(targetEntity="Student")
     */
    protected $student;

    /**
     * @var Subject
     *
     * @OGM\EndNode(targetEntity="Subject")
     */
    protected $subject;

    /**
     * @var int
     *
     * @OGM\Property(type="int")
     */
    protected $timesEnrolled;

    public function __construct(Student $student, Subject $subject, int $timesEnrolled)
    {
        $this->student = $teacher;
        $this->subject = $subject;
        $this->timesEnrolled = $timesEnrolled;
    }

    function __get($prop) { return $this->$prop; }
  	function __set($prop, $val) { $this->$prop = $val; return $this; }
}
