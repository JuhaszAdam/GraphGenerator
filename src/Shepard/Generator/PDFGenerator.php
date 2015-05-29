<?php

namespace Shepard\Generator;

use Shepard\Entity\EntityInterface;
use Symfony\Component\Process\Process;

class PDFGenerator
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
     * @param EntityInterface[] $entity
     * @param string            $savePath
     * @param string            $saveName
     * @returns bool
     */
    public function draw(array $entity, $savePath = "graph/", $saveName = "g")
    {
        if (count($entity) <= 200) {
            $this->fileContentDot = "digraph my_graph{" . PHP_EOL;
            $this->fileContentEdgesDot = "";
            $graphConfig = '';
            foreach ($this->graphStyle as $style) {
                $graphConfig .= $style . ";" . PHP_EOL;
            }
            $this->fileContentDot .= ($graphConfig);

            $currentNode = 'node [ label = "' . $entity[0]->getLabel1() . '|' . $entity[0]->getLabel3() . '"';
            foreach ($this->nodeStyle as $style) {
                $currentNode .= ", " . $style;
            }
            $currentNode .= ' ] ' . $entity[0]->getId() . ';' . PHP_EOL;
            $this->fileContentDot .= ($currentNode);

            $this->checkNodes($entity[0], $savePath, $saveName);

            $this->fileContentDot .= $this->fileContentEdgesDot . '}';

            $gvFile = fopen($savePath . $saveName . '.gv', "w");
            fwrite($gvFile, $this->fileContentDot);
            fclose($gvFile);

            (new Process("circo " . $savePath . $saveName . ".gv -Tpdf -o " . $savePath . $saveName . ".pdf"))->run();
            (new Process("rm " . $savePath . $saveName . ".gv"))->run();

            return true;
        } else {
            return false;
        }
    }

    /**
     * @param EntityInterface $entity
     */
    private function checkNodes($entity)
    {
        foreach ($entity->getNodes() as $entityNode) {
            if ($entityNode !== null) {
                $currentNode = 'node [ label = "' . $entityNode->getLabel1() . '|' . $entityNode->getLabel3() . '"';
                foreach ($this->nodeStyle as $style) {
                    $currentNode .= ", " . $style;
                }
                $currentNode .= ' ] ' . $entityNode->getId() . ';' . PHP_EOL;
                $this->fileContentDot .= ($currentNode);

                $this->fileContentEdgesDot .= $entity->getId() . ' -> ' . $entityNode->getId() . '[ ';
                foreach ($this->edgeStyle as $style) {
                    $this->fileContentEdgesDot .= $style . ' , ';
                }
                $this->fileContentEdgesDot .= 'len="10.0"]; ' . PHP_EOL;

                $this->checkNodes($entityNode);
            }
        }
    }
}
