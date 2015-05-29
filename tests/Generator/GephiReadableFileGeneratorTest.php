<?php

namespace Shepard\Tests\Generator;

use Shepard\Generator\GephiReadableFileGenerator;
use Shepard\Tests\Entity\ExampleEntityProvider;

class GephiReadableFileGeneratorTest extends \PHPUnit_Framework_TestCase
{
    public function testDrawGexf()
    {
        $generator = new GephiReadableFileGenerator();
        $userList = ExampleEntityProvider::generate(10000, 3, 100);
        $this->assertTrue($generator->draw($userList[0], "tests/drawTests/test_gexf/graph.gexf"));
    }
}
