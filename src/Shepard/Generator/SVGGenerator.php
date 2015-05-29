<?php

namespace Shepard\Generator;

use Shepard\Entity\EntityInterface;
use Symfony\Component\Process\Process;

class SVGGenerator
{
    /**
     * @var array
     */
    private $graphStyle = [
        'rankdir=LR',
        'size="8,5"',
        'bgcolor="white"'
    ];

    /**
     * @var array
     */
    private $nodeStyle = [
        "shape = record",
        "penwidth = 2.0",
        "color = Black",
        "style = filled",
        "fillcolor = white"
    ];

    /**
     * @var array
     */
    private $edgeStyle = [];

    /**
     * @var string
     */
    private $fileContentDot = "";

    /**
     * @var string
     */
    private $fileContentEdgesDot = "";

    /**
     * @param array|null $graphStyle
     * @param array|null $nodeStyle
     * @param array|null $edgeStyle
     */
    public function __construct($graphStyle = null, $nodeStyle = null, $edgeStyle = null)
    {
        if ($graphStyle !== null) {
            $this->graphStyle = $graphStyle;
        }
        if ($nodeStyle !== null) {
            $this->nodeStyle = $nodeStyle;
        }
        if ($edgeStyle !== null) {
            $this->edgeStyle = $edgeStyle;
        }
    }

    /**
     * @param EntityInterface $entity
     * @param string          $savePath
     * @param string          $saveName
     * @returns bool
     */
    public function draw($entity, $savePath = "graph/", $saveName = "id")
    {
        $this->checkNodes($entity, $savePath, $saveName);
        (new Process("rm " . $savePath . $saveName . ".gv"))->run();

        return true;
    }

    /**
     * @param EntityInterface $entity
     * @param string          $savePath
     * @param string          $saveName
     */
    private function checkNodes($entity, $savePath, $saveName)
    {
        if (!empty($entity->getNodes())) {
            $this->generateSvgFile($entity, $savePath, $saveName);

            foreach ($entity->getNodes() as $entityNode) {
                if ($entityNode !== null) {
                    $this->checkNodes($entityNode, $savePath, $saveName);
                }
            }
        }
    }

    /**
     * @param EntityInterface $entity
     * @param string          $savePath
     * @param string          $saveName
     */
    private function generateSvgFile($entity, $savePath, $saveName)
    {
        $this->fileContentDot = "digraph my_graph {" . PHP_EOL;
        $this->fileContentEdgesDot = "";

        $graphConfig = '';
        foreach ($this->graphStyle as $style) {
            $graphConfig .= $style . ";" . PHP_EOL;
        }
        $this->fileContentDot .= ($graphConfig);

        $currentNode = 'node [ label = "' . $entity->getLabel1() . '|' . $entity->getLabel3() . '"';
        foreach ($this->nodeStyle as $style) {
            $currentNode .= ", " . $style;
        }
        $currentNode .= ' ] ' . $entity->getId() . ';' . PHP_EOL;
        $this->fileContentDot .= ($currentNode);

        foreach ($entity->getNodes() as $entityNode) {
            if ($entityNode !== null) {
                $currentNode = 'node [ label = "' . $entityNode->getLabel1() . '|' . $entityNode->getLabel3() . '"';
                if (!empty($entityNode->getNodes())) {
                    $currentNode .= ' , URL="' . $saveName . $entityNode->getId() . '.svg" ';
                }
                foreach ($this->nodeStyle as $style) {
                    $currentNode .= ", " . $style;
                }
                $currentNode .= ' ] ' . $entityNode->getId() . ';' . PHP_EOL;
                $this->fileContentDot .= ($currentNode);

                $this->fileContentEdgesDot .= $entity->getId() . ' -> ' . $entityNode->getId() . '[ ';
                foreach ($this->edgeStyle as $style) {
                    $this->fileContentEdgesDot .= $style . ' , ';
                }
                $this->fileContentEdgesDot .= ']; ' . PHP_EOL;
            }
        }

        $this->fileContentDot .= $this->fileContentEdgesDot . '}';

        $gvFile = fopen($savePath . $saveName . '.gv', "w");
        fwrite($gvFile, $this->fileContentDot);
        fclose($gvFile);

        (new Process("circo " . $savePath . $saveName . ".gv -Tsvg -o "
            . $savePath . $saveName . $entity->getId() . ".svg"))->run();
    }
}
