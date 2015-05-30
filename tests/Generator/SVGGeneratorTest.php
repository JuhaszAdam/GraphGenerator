<?php

namespace Shepard\Tests\Generator;

use Shepard\Generator\SVGGenerator;
use Shepard\Style\Style;
use Shepard\Tests\Entity\ExampleEntityProvider;
use Shepard\Tests\Storage\ExampleStorage;

class SVGGeneratorTest extends \PHPUnit_Framework_TestCase
{
    public function testDrawSvg()
    {
        $generator = new SVGGenerator(new ExampleStorage(), new Style());
        $userList = ExampleEntityProvider::getFixedUserList();
        $generator->draw($userList[0], "tests/drawTests/test_svg/test1/", "id-");

        $this->assertTrue(true);
    }

    public function testConstructor()
    {
        $generator = new SVGGenerator(
            new ExampleStorage(),
            new Style(
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
            )
        );

        $userList = ExampleEntityProvider::getFixedUserList();
        $generator->draw($userList[0], "tests/drawTests/test_svg/test2/", "id-");

        $this->assertTrue(true);
    }
}
