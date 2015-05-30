<?php

namespace Shepard\Entity;

interface EntityInterface
{
    /**
     * @return string/int
     */
    public function getId();

    /**
     * @return string
     */
    public function getLabel1();

    /**
     * @return string
     */
    public function getLabel2();

    /**
     * @return string
     */
    public function getLabel3();

    /**
     * @return EntityInterface[]
     */
    public function getNodes();
}
