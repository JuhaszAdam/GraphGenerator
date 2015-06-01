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
        $generator = new SVGGenerator(new LocalStorage("/tmp/Shepard/GraphGenerator/Draw Tests/Svg/Test 1/"), new Style("circo"));
        $userList = ExampleEntityProvider::getFixedUserList();
        $generator->draw($userList[0], "id-");

        $this->assertTrue(true);
    }

    public function testStyle()
    {
        $generator = new SVGGenerator(
            new LocalStorage("/tmp/Shepard/GraphGenerator/Draw Tests/Svg/Test 2/"),
            new Style(
                "fdp",
                [
                    'rankdir=TB',
                    'bgcolor="#121212:#343434"',
                    'gradientangle = 90',
                    'overlap="prism"',
                    'splines=true',
                    'size="20.0,10.0!"',
                    'ratio="fill"',
                    'labelloc = "top"',
                    'labeljust = "left"',
                    'fontsize=25',
                    'fontcolor=white'
                ],
                [
                    "shape = record",
                    "penwidth = 2.0",
                    "color = black",
                    "fontcolor = white",
                    "style = filled",
                    'fillcolor = "#111111:#333333"',
                    'gradientangle = 90'
                ],
                [
                    "shape = record",
                    "penwidth = 2.0",
                    'color = black',
                    "fontcolor = white",
                    "style = filled",
                    'fillcolor = "#111133:#333355"',
                    'gradientangle = 90'
                ],
                [
                    "shape = record",
                    "penwidth = 2.0",
                    'color = white',
                    'fontcolor = white',
                    "style = filled",
                    'fillcolor = "#111111:#333333"',
                    'gradientangle = 90'
                ],
                [
                    'color = "white"',
                    "len=1.0"
                ]
            )
        );

        $userList = ExampleEntityProvider::generate(5000, 3, 100);
        $generator->draw($userList[0], "id-");

        $this->assertTrue(true);
    }
}
