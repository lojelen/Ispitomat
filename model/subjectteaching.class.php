<?php

namespace Ispitomat;

use GraphAware\Neo4j\OGM\Annotations as OGM;

/**
 *
 * @OGM\RelationshipEntity(type="TEACHES")
 */
class SubjectTeaching
{
    /**
     * @var int
     *
     * @OGM\GraphId()
     */
    protected $neo4jID;

    /**
     * @var Teacher
     *
     * @OGM\StartNode(targetEntity="Teacher")
     */
    protected $teacher;

    /**
     * @var Subject
     *
     * @OGM\EndNode(targetEntity="Subject")
     */
    protected $subject;

    /**
     * @var string
     *
     * @OGM\Property(type="string")
     */
    protected $startOfTeaching;

    /**
     * @var string
     *
     * @OGM\Property(type="role")
     */
    protected $role;

    public function __construct(Teacher $teacher, Subject $subject, string $startOfTeaching, string $role)
    {
        $this->teacher = $teacher;
        $this->subject = $subject;
        $this->startOfTeaching = $startOfTeaching;
        $this->role = $role;
    }

    function __get($prop) { return $this->$prop; }
  	function __set($prop, $val) { $this->$prop = $val; return $this; }
}
