<?php

namespace Shepard\Tests\Generator;

use Doctrine\Instantiator\Exception\InvalidArgumentException;
use Shepard\Generator\PDFGenerator;
use Shepard\Storage\LocalStorage;
use Shepard\Storage\StorageInterface;
use Shepard\Style\Style;
use Shepard\Tests\Entity\ExampleEntityProvider;
use Symfony\Component\Stopwatch\Stopwatch;

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

    public function testGenerationExecutableTimes()
    {
        $generator = [];
        $generatorExecutables = ["circo", "dot", "twopi", "neato", "fdp", "sfdp"];

        foreach ($generatorExecutables as $generatorExecutable) {
            $generator[$generatorExecutable] =
                new PDFGenerator(new LocalStorage(
                    "/tmp/Shepard/GraphGenerator/Draw Tests/Pdf/StopwatchTests/"),
                    new Style($generatorExecutable)
                );
        }

        $userList = ExampleEntityProvider::generate(200, 3, 50);

        $stopwatch = new Stopwatch();
        $stopwatch->start('timer');

        foreach ($generatorExecutables as $generatorExecutable) {
            $generator[$generatorExecutable]->draw($userList, $generatorExecutable);
            $stopwatch->lap('timer');
        }

        $event = $stopwatch->stop('timer');
        $periods = $event->getPeriods();

        $results = "Generating PDF files" . PHP_EOL . PHP_EOL;
        $i = 0;
        foreach ($generatorExecutables as $generatorExecutable) {
            $results .= $generatorExecutable . " - " . $periods[$i++]->getDuration() . " ms" . PHP_EOL;
        }

        $file = fopen('tests/stopwatch_pdf_results.txt', "w");
        fputs($file, $results);
        fclose($file);

        $this->assertTrue(true);
    }

    public function testGenerationSplineTimes()
    {
        $generator = [];
        $generatorSplines = ["false", "true", "ortho", "scalexy", "prism", "compress", "vpsc", "voronoi"];

        foreach ($generatorSplines as $spline) {
            $generator[$spline] =
                new PDFGenerator(new LocalStorage(
                    "/tmp/Shepard/GraphGenerator/Draw Tests/Pdf/StopwatchTests2/"),
                    new Style("sfdp",
                        [
                            'rankdir=TB',
                            'size="8,5"',
                            'bgcolor="white"',
                            'labelloc = "top"',
                            'labeljust = "left"',
                            'fontsize=25',
                            'overlap="' . $spline . '"'
                        ],
                        [
                            "shape = record",
                            "penwidth = 2.0",
                            "color = black",
                            "style = filled",
                            "fillcolor = white"
                        ]
                    )
                );
        }

        $userList = ExampleEntityProvider::generate(200, 3, 50);

        $stopwatch = new Stopwatch();
        $stopwatch->start('timer');

        foreach ($generatorSplines as $spline) {
            $generator[$spline]->draw($userList, $spline);
            $stopwatch->lap('timer');
        }

        $event = $stopwatch->stop('timer');
        $periods = $event->getPeriods();

        $results = "Generating PDF files" . PHP_EOL . PHP_EOL;
        $i = 0;
        foreach ($generatorSplines as $spline) {
            $results .= $spline . " - " . $periods[$i++]->getDuration() . " ms" . PHP_EOL;
        }

        $file = fopen('tests/stopwatch_pdf_spline_results.txt', "w");
        fputs($file, $results);
        fclose($file);

        $this->assertTrue(true);
    }
}
