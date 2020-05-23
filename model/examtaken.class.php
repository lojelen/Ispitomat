<?php

namespace Ispitomat;

use GraphAware\Neo4j\OGM\Annotations as OGM;

/**
 *
 * @OGM\RelationshipEntity(type="TAKES")
 */
class ExamTaken
{
    /**
     * @var int
     *
     * @OGM\GraphId()
     */
    protected $id;

    /**
     * @var Student
     *
     * @OGM\StartNode(targetEntity="Student")
     */
    protected $student;

    /**
     * @var Exam
     *
     * @OGM\EndNode(targetEntity="Exam")
     */
    protected $exam;

    /**
     * @var boolean
     *
     * @OGM\Property(type="boolean")
     */
    protected $passed;

    /**
     * @var float
     *
     * @OGM\Property(type="float")
     */
    protected $score;

    /**
     * @var int
     *
     * @OGM\Property(type="int")
     */
    protected $grade;

    public function __construct(Student $student, Exam $exam, bool $passed, float $score, int $grade=null)
    {
        $this->student = $student;
        $this->exam = $exam;
        $this->passed = $passed;
        $this->score = $score;
        $this->grade = $grade;
    }

    function __get($prop) { return $this->$prop; }
  	function __set($prop, $val) { $this->$prop = $val; return $this; }
}
