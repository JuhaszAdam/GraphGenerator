<?php

namespace Shepard\Tests\Generator;

use Shepard\Generator\SVGGenerator;
use Shepard\Storage\LocalStorage;
use Shepard\Style\Style;
use Shepard\Tests\Entity\ExampleEntityProvider;
use Symfony\Component\Stopwatch\Stopwatch;

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

    public function testGenerationCircoTimes()
    {
        $generator = new SVGGenerator(new LocalStorage("/tmp/Shepard/GraphGenerator/Draw Tests/Svg/Test 3/"), new Style("circo"));
        $userList = [];
        for ($i = 1; $i <= 10; $i++) {
            $userList[$i] = ExampleEntityProvider::generate($i * 100, 3, 50);
        }

        $stopwatch = new Stopwatch();
        $stopwatch->start('timer');

        for ($i = 1; $i <= 10; $i++) {
            $generator->draw($userList[$i][0], "id-");
            $stopwatch->lap('timer');
        }

        $event = $stopwatch->stop('timer');
        $periods = $event->getPeriods();

        $results = "Generating Circo files" . PHP_EOL . PHP_EOL;
        for ($i = 1; $i <= 10; $i++) {
            $results .= ($i * 100) . " entities: " . $periods[$i - 1]->getDuration() . " ms" . PHP_EOL;
        }

        $file = fopen('tests/stopwatch_results.txt', "w");
        fputs($file, $results);
        fclose($file);

        $this->assertTrue(true);
    }
}
