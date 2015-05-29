<?php

namespace Shepard\Tests\Generator;

use Shepard\Generator\SVGGenerator;
use Shepard\Tests\Entity\ExampleEntityProvider;

class SVGGeneratorTest extends \PHPUnit_Framework_TestCase
{
    public function testDrawSvg()
    {
        $generator = new SVGGenerator();
        $userList = ExampleEntityProvider::getFixedUserList();
        $this->assertTrue($generator->draw($userList[0], "tests/drawTests/test_svg/test1/", "id-"));
    }

    public function testConstructor()
    {
        $generator = new SVGGenerator(
            [
                'rankdir=LR',
                'size="8,5"',
                'bgcolor="gray"'
            ],
            [
                "shape = record",
                "penwidth = 2.0",
                "color = blue",
                "style = filled",
                "fillcolor = white"
            ],
            [
                "color = blue"
            ]
        );

        $userList = ExampleEntityProvider::getFixedUserList();
        $this->assertTrue($generator->draw($userList[0], "tests/drawTests/test_svg/test2/", "id-"));
    }
}
