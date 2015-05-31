<?php

namespace Shepard\Tests\Generator;

use Doctrine\Instantiator\Exception\InvalidArgumentException;
use Shepard\Generator\PDFGenerator;
use Shepard\Storage\LocalStorage;
use Shepard\Style\Style;
use Shepard\Tests\Entity\ExampleEntityProvider;

class PDFGeneratorTest extends \PHPUnit_Framework_TestCase
{
    public function testDrawPdf()
    {
        $generator = new PDFGenerator(new LocalStorage(), new Style("circo"));
        $userList = ExampleEntityProvider::generate(150, 3, 5);
        $generator->draw($userList, "tests/drawTests/test_pdf/g");

        $this->assertTrue(true);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testDrawTooManyEntities()
    {
        $generator = new PDFGenerator(new LocalStorage(), new Style("circo"));
        $userList = ExampleEntityProvider::generate(500, 3, 5);

        $generator->draw($userList, "tests/drawTests/test_pdf/g");

        $this->assertTrue(true);
    }

    public function testStyle()
    {
        $generator = new PDFGenerator(
            new LocalStorage(),
            new Style(
                "circo",
                [
                    'rankdir=TB',
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

        $userList = ExampleEntityProvider::getFixedUserList();
        $generator->draw($userList, "tests/drawTests/test_pdf/g2");

        $this->assertTrue(true);
    }
}
