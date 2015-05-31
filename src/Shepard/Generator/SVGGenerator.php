<?php

namespace Shepard\Generator;

use Shepard\Entity\EntityInterface;
use Symfony\Component\Process\Process;

class SVGGenerator extends AbstractGenerator
{
    /**
     * @param EntityInterface $entity
     * @param string          $filePath
     * @param string          $fileName
     */
    public function draw(EntityInterface $entity, $filePath = "graph/", $fileName = "id")
    {
        $this->buildNodes($entity, $filePath, $fileName);
    }

    /**
     * @param EntityInterface $entity
     * @param string          $filePath
     * @param string          $fileName
     */
    private function buildNodes(EntityInterface $entity, $filePath, $fileName)
    {
        if (!empty($entity->getNodes())) {
            $this->generateSvgFile($entity, $filePath, $fileName);

            foreach ($entity->getNodes() as $entityNode) {
                if ($entityNode !== null) {
                    $this->buildNodes($entityNode, $filePath, $fileName);
                }
            }
        }
    }

    /**
     * @param EntityInterface $entity
     * @param string          $filePath
     * @param string          $fileName
     */
    private function generateSvgFile(EntityInterface $entity, $filePath, $fileName)
    {
        $fileContent = "digraph my_graph {" . PHP_EOL;
        $fileContentEdges = "";

        $graphConfig = '';
        foreach ($this->style->getGraphStyle() as $style) {
            $graphConfig .= $style . ";" . PHP_EOL;
        }
        $fileContent .= $graphConfig;

        $currentNode = 'node [ label = "' . $entity->getLabel1() . '|' . $entity->getLabel3() . '"';

        foreach ($this->style->getNodeStyle() as $style) {
            $currentNode .= ", " . $style;
        }
        $currentNode .= ' , URL="" ';

        $currentNode .= ' ] ' . $entity->getId() . ';' . PHP_EOL;
        $fileContent .= $currentNode;

        foreach ($entity->getNodes() as $entityNode) {
            if ($entityNode !== null) {
                $currentNode = 'node [ label = "' . $entityNode->getLabel1() . '|' . $entityNode->getLabel3() . '"';
                if (!(empty($entityNode->getNodes()))) {
                    foreach ($this->style->getActiveNodeStyle() as $style) {
                        $currentNode .= ", " . $style;
                    }
                    $currentNode .= ' , URL="' . $fileName . $entityNode->getId() . '.svg" ';
                } else {
                    foreach ($this->style->getNodeStyle() as $style) {
                        $currentNode .= ", " . $style;
                    }
                    $currentNode .= ' , URL="" ';
                }
                $currentNode .= ' ] ' . $entityNode->getId() . ';' . PHP_EOL;
                $fileContent .= ($currentNode);

                $fileContentEdges .= $entity->getId() . ' -> ' . $entityNode->getId() . '[ ';
                foreach ($this->style->getEdgeStyle() as $style) {
                    $fileContentEdges .= $style . ' , ';
                }
                $fileContentEdges .= ']; ' . PHP_EOL;
            }
        }
        $fileContent .= $fileContentEdges . '}';

        $this->store(($filePath . $fileName . $entity->getId()), $fileContent);
    }

    /**
     * @param $filePath
     * @param $fileContent
     */
    private function store($filePath, $fileContent)
    {
        $tempFile = tmpfile();
        $path = stream_get_meta_data($tempFile)['uri'];
        fwrite($tempFile, $fileContent);

        (new Process($this->style->getGraphType() . " " . $path . " -Tsvg -o " . $filePath . ".svg"))->run();
        fclose($tempFile);

        $this->storage->storeContent($filePath . ".svg", file_get_contents($filePath . ".svg"));
    }
}
