<?php

namespace Shepard\Tests\Generator;

use Shepard\Generator\GephiReadableFileGenerator;
use Shepard\Storage\LocalStorage;
use Shepard\Storage\StorageInterface;
use Shepard\Style\Style;
use Shepard\Tests\Entity\ExampleEntityProvider;

class GephiReadableFileGeneratorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var StorageInterface
     */
    private $storage;

    protected function setUp()
    {
        $this->storage = new LocalStorage("/tmp/Shepard/GraphGenerator/Draw Tests/Gexf/");
    }

    public function testDrawGexf()
    {
        $generator = new GephiReadableFileGenerator($this->storage, new Style("circo"));
        $userList = ExampleEntityProvider::generate(10000, 3, 100);
        $generator->draw($userList[0], "graph.gexf");

        $this->assertTrue(true);
    }
}
