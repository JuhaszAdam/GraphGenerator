<?php

namespace Shepard\Tests\Generator;

use Shepard\Generator\SVGGenerator;
use Shepard\Storage\LocalStorage;
use Shepard\Style\Style;
use Shepard\Tests\Entity\ExampleEntityProvider;

class SVGGeneratorTest extends \PHPUnit_Framework_TestCase
{
    public function testDrawSvg()
    {
        $generator = new SVGGenerator(new LocalStorage(), new Style("circo"));
        $userList = ExampleEntityProvider::generate(200, 3, 15);
        $generator->draw($userList[0], "tests/drawTests/test_svg/test1/", "id-");

        $this->assertTrue(true);
    }

    public function testStyle()
    {
        $generator = new SVGGenerator(
            new LocalStorage(),
            new Style(
                "fdp",
                [
                    'rankdir=LR',
                    'bgcolor="#454545"',
                    'overlap="prism"',
                    'splines="spline"',
                    'splines=true',
                    'size="20.0,10.0!"',
                    'ratio="fill"'
                ],
                [
                    "shape = record",
                    "penwidth = 2.0",
                    "color = white",
                    "fontcolor = white",
                    "style = filled",
                    'fillcolor = "#111111:#333333"',
                    'gradientangle = 90'
                ],
                [
                    "shape = record",
                    "penwidth = 2.0",
                    "color = white",
                    "fontcolor = white",
                    "style = filled",
                    'fillcolor = "#111133:#333355"',
                    'gradientangle = 90'
                ],
                [
                    'color = "white"',
                    "len=4.0"
                ]
            )
        );

       // $userList = ExampleEntityProvider::getFixedUserList();
        $userList = ExampleEntityProvider::generate(2000, 3, 100);
        $generator->draw($userList[0], "tests/drawTests/test_svg/test2/", "id-");

        $this->assertTrue(true);
    }
}
