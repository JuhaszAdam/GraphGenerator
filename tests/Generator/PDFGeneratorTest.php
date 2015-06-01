<?php

namespace Shepard\Tests\Generator;

use Doctrine\Instantiator\Exception\InvalidArgumentException;
use Shepard\Generator\PDFGenerator;
use Shepard\Storage\LocalStorage;
use Shepard\Storage\StorageInterface;
use Shepard\Style\Style;
use Shepard\Tests\Entity\ExampleEntityProvider;

class PDFGeneratorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var StorageInterface
     */
    private $storage;

    protected function setUp()
    {
        $this->storage = new LocalStorage("/tmp/Shepard/GraphGenerator/Draw Tests/Pdf/");
    }

    public function testDrawPdf()
    {
        $generator = new PDFGenerator($this->storage, new Style("circo"));
        $userList = ExampleEntityProvider::generate(150, 3, 5);
        $generator->draw($userList, "150Entities");

        $this->assertTrue(true);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testDrawTooManyEntities()
    {
        $generator = new PDFGenerator($this->storage, new Style("circo"));
        $userList = ExampleEntityProvider::generate(1000, 3, 5);

        $generator->draw($userList, "This test should fail");

        $this->assertTrue(true);
    }

    public function testStyle()
    {
        $generator = new PDFGenerator(
            $this->storage,
            new Style(
                "dot",
                [
                    'rankdir=TB',
                    'bgcolor="#666666:#999999"',
                    'gradientangle = 90',
                    'overlap="prism"',
                    'splines="spline"',
                    'splines=true',
                    'size="20.0,10.0!"',
                    'ratio="fill"'
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
                    'color = white',
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
                    'color = "black"',
                    "len=4.0"
                ]
            )
        );

        $userList = ExampleEntityProvider::getFixedUserList();
        $generator->draw($userList, "FixedEntities");

        $this->assertTrue(true);
    }

    public function testStyle2()
    {
        $generator = new PDFGenerator(
            $this->storage,
            new Style(
                "sfdp",
                [
                    'rankdir=TB',
                    'bgcolor="#666666:#999999"',
                    'gradientangle = 90',
                    'overlap="scalexy"',
                    'splines="polylines"',
                    'concentrate=true'
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
                    'color = white',
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
                    'color = "black"'
                ]
            )
        );

        $userList = ExampleEntityProvider::generate(500, 20, 50);
        $generator->draw($userList, "500EntitiesSFDP");

        $this->assertTrue(true);
    }
}
