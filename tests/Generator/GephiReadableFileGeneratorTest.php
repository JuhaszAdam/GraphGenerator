<?php

namespace Shepard\Tests\Generator;

use Shepard\Generator\GephiReadableFileGenerator;
use Shepard\Style\Style;
use Shepard\Tests\Entity\ExampleEntityProvider;
use Shepard\Tests\Storage\ExampleStorage;

class GephiReadableFileGeneratorTest extends \PHPUnit_Framework_TestCase
{
    public function testDrawGexf()
    {
        $generator = new GephiReadableFileGenerator(new ExampleStorage(), new Style("circo"));
        $userList = ExampleEntityProvider::generate(10000, 3, 100);
        $generator->draw($userList[0], "tests/drawTests/test_gexf/graph.gexf");

        $this->assertTrue(true);
    }
}
