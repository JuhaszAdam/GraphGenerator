<?php

namespace Shepard\Tests\Generator;

use Doctrine\Instantiator\Exception\InvalidArgumentException;
use Shepard\Generator\PDFGenerator;
use Shepard\Style\Style;
use Shepard\Tests\Entity\ExampleEntityProvider;
use Shepard\Tests\Storage\ExampleStorage;

class PDFGeneratorTest extends \PHPUnit_Framework_TestCase
{
    public function testDrawPdf()
    {
        $generator = new PDFGenerator(new ExampleStorage(), new Style());
        $userList = ExampleEntityProvider::generate(150, 3, 5);
        $generator->draw($userList, "tests/drawTests/test_pdf/g");

        $this->assertTrue(true);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testDrawTooManyEntities()
    {
        $generator = new PDFGenerator(new ExampleStorage(), new Style());
        $userList = ExampleEntityProvider::generate(500, 3, 5);
        $generator->draw($userList, "tests/drawTests/test_pdf/g");

        $this->assertTrue(true);
    }

    public function testConstructor()
    {
        $generator = new PDFGenerator(
            new ExampleStorage(),
            new Style(
                [
                    'rankdir=LR',
                    'size="8,5"',
                    'bgcolor="gray"'
                ],
                [
                    "shape = box",
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

        $userList = ExampleEntityProvider::generate(20, 3, 5);
        $generator->draw($userList, "tests/drawTests/test_pdf/g2");

        $this->assertTrue(true);
    }
}
