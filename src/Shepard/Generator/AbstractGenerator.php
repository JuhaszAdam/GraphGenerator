<?php

namespace Shepard\Generator;

use Shepard\Storage\StorageInterface;
use Shepard\Style\Style;

abstract class AbstractGenerator
{
    /**
     * @var StorageInterface $storage
     */
    protected $storage;

    /**
     * @var Style $style
     */
    protected $style;

    public function __construct(StorageInterface $storage, Style $style)
    {
        $this->storage = $storage;
        $this->style = $style;
    }
}
