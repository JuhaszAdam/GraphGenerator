<?php

namespace Shepard\Tests\Generator;

use Shepard\Generator\PDFGenerator;
use Shepard\Tests\Entity\ExampleEntityProvider;

class PDFGeneratorTest extends \PHPUnit_Framework_TestCase
{
    public function testDrawPdf()
    {
        $generator = new PDFGenerator();
        $userList = ExampleEntityProvider::generate(150, 3, 5);
        $this->assertTrue($generator->draw($userList, "tests/drawTests/test_pdf/", "g"));
    }

    public function testDrawTooManyEntities()
    {
        $generator = new PDFGenerator();
        $userList = ExampleEntityProvider::generate(500, 3, 5);
        $this->assertFalse($generator->draw($userList, "tests/drawTests/test_pdf/", "g"));
    }

    public function testConstructor()
    {
        $generator = new PDFGenerator(
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
        );

        $userList = ExampleEntityProvider::generate(20, 3, 5);
        $this->assertTrue($generator->draw($userList, "tests/drawTests/test_pdf/", "g2"));
    }
}
