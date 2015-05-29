<?php

namespace Shepard\Tests\Entity;

use Shepard\Entity\EntityInterface;

class ExampleEntity implements EntityInterface
{
    /**
     * @var int
     */
    private $id;
    /**
     * @var string
     */
    private $label1;
    /**
     * @var string
     */
    private $label2;
    /**
     * @var string
     */
    private $label3;
    /**
     * @var ExampleEntity[]
     */
    private $nodes;

    /**
     * @param int $id
     * @param string $label1
     * @param string $label2
     * @param string $label3
     * @param ExampleEntity[] $nodes
     */
    public function __construct($id = -1,
                                $label1 = "Unknown",
                                $label2 = "00-00-000-0000",
                                $label3 = "unknown@unknown.com",
                                $nodes = [])
    {
        $this->id = $id;
        $this->label1 = $label1;
        $this->label2 = $label2;
        $this->label3 = $label3;
        $this->nodes = $nodes;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getLabel1()
    {
        return $this->label1;
    }

    /**
     * @param string $label1
     */
    public function setLabel1($label1)
    {
        $this->label1 = $label1;
    }

    /**
     * @return string
     */
    public function getLabel2()
    {
        return $this->label2;
    }

    /**
     * @param string $label2
     */
    public function setLabel2($label2)
    {
        $this->label2 = $label2;
    }

    /**
     * @return string
     */
    public function getLabel3()
    {
        return $this->label3;
    }

    /**
     * @param string $label3
     */
    public function setLabel3($label3)
    {
        $this->label3 = $label3;
    }

    /**
     * @return ExampleEntity[]
     */
    public function getNodes()
    {
        return $this->nodes;
    }

    /**
     * @param ExampleEntity[] $nodes
     */
    public function setNodes($nodes)
    {
        $this->nodes = $nodes;
    }
}
