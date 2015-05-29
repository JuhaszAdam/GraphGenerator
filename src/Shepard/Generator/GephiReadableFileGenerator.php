<?php

namespace Shepard\Generator;

use Shepard\Entity\EntityInterface;

class GephiReadableFileGenerator
{
    /**
     * @var string
     */
    private $fileContent = "";

    /**
     * @var string
     */
    private $fileContentEdges = "";

    /**
     * @var int
     */
    private $nodeId = 0;

    /**
     * @var int
     */
    private $edgeId = 0;

    /**
     * @param EntityInterface $entity
     * @param string          $savePath
     * @returns bool
     */
    public function draw($entity, $savePath = "graph.gexf")
    {
        $this->fileContent = '<?' . 'xml version="1.0" encoding="UTF-8"?>
        <gexf xmlns="http://www.gexf.net/1.2draft" version="1.2">
        <meta lastmodifieddate="2009-03-20">
        <creator>Gexf.net</creator>
        <description>Graph</description>
        </meta>
        <graph mode="static" defaultedgetype="directed">
        <nodes>' . PHP_EOL;

        $this->setUpGraph($entity);

        $gvFile = fopen($savePath, "w");
        fwrite($gvFile, $this->fileContent);
        fclose($gvFile);

        return true;
    }

    /**
     * @param EntityInterface $entity
     */
    public function setUpGraph($entity)
    {
        $this->fileContent .= '<node id="' . $this->nodeId++ . '" label="' . $entity->getLabel1()
            . PHP_EOL . $entity->getLabel3() . '" />' . PHP_EOL;

        $this->checkNodes($entity);

        $this->fileContent .= '</nodes>' . PHP_EOL . '<edges>' . PHP_EOL
            . $this->fileContentEdges . PHP_EOL . '</edges></graph></gexf>';
    }

    /**
     * @param EntityInterface $entity
     */
    private function checkNodes($entity)
    {
        $nodes = $entity->getNodes();
        foreach ($nodes as $entityNode) {
            if ($entityNode !== null) {
                $this->fileContent .= '<node id="' . $this->nodeId++ . '" label="' . $entityNode->getLabel1()
                    . PHP_EOL . $entityNode->getLabel3() . '"/>' . PHP_EOL;

                $this->fileContentEdges .= '<edge id="' . $this->edgeId++ . '" source="'
                    . $entity->getId() . '" target="' . $entityNode->getId() . '" />' . PHP_EOL;

                $this->checkNodes($entityNode);
            }
        }
    }
}
