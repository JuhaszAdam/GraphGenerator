<?php

namespace Shepard\Generator;

use Shepard\Entity\EntityInterface;

class GephiReadableFileGenerator extends AbstractGenerator
{
    /**
     * @var int
     */
    private $nodeId = 0;

    /**
     * @var int
     */
    private $edgeId = 0;

    private $header = '<?xml version="1.0" encoding="UTF-8"?>
        <gexf xmlns="http://www.gexf.net/1.2draft" version="1.2">
        <meta lastmodifieddate="2009-03-20">
        <creator>Gexf.net</creator>
        <description>Graph</description>
        </meta>
        <graph mode="static" defaultedgetype="directed">
        <nodes>';

    /**
     * @param EntityInterface $entity
     * @param string          $fileName
     */
    public function draw(EntityInterface $entity, $fileName = "graph.gexf")
    {
        $fileContent = $this->header;
        $fileContentEdges = "";

        $fileContent .= '<node id="' . $this->nodeId++ . '" label="' . $entity->getLabel1()
            . PHP_EOL . $entity->getLabel3() . '" />' . PHP_EOL;

        $this->buildNodes($entity, $fileContent, $fileContentEdges);
        $fileContent .= '</nodes>' . PHP_EOL . '<edges>' . PHP_EOL
            . $fileContentEdges . PHP_EOL . '</edges></graph></gexf>';

        $this->store($fileName, $fileContent);
    }

    /**
     * @param EntityInterface $entity
     * @param string          $fileContent
     * @param string          $fileContentEdges
     */
    private function buildNodes(EntityInterface $entity, &$fileContent, &$fileContentEdges)
    {
        $nodes = $entity->getNodes();
        foreach ($nodes as $entityNode) {
            if ($entityNode !== null) {
                $fileContent .= '<node id="' . $this->nodeId++ . '" label="' . $entityNode->getLabel1()
                    . PHP_EOL . $entityNode->getLabel3() . '"/>' . PHP_EOL;

                $fileContentEdges .= '<edge id="' . $this->edgeId++ . '" source="'
                    . $entity->getId() . '" target="' . $entityNode->getId() . '" />' . PHP_EOL;

                $this->buildNodes($entityNode, $fileContent, $fileContentEdges);
            }
        }
    }

    /**
     * @param $fileName
     * @param $fileContent
     */
    private function store($fileName, $fileContent)
    {
        $tempFile = tmpfile();
        $path = stream_get_meta_data($tempFile)['uri'];
        fwrite($tempFile, $fileContent);
        $this->storage->storeContent($fileName, file_get_contents($path));
        fclose($tempFile);
    }
}
