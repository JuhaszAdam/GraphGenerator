<?php

namespace Shepard\Generator;

use Shepard\Entity\EntityInterface;

class SVGGenerator extends AbstractGenerator
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
     * @param EntityInterface $entity
     * @param string          $filePath
     * @param string          $fileName
     */
    public function draw(EntityInterface $entity, $filePath = "graph/", $fileName = "id")
    {
        $this->buildNodes($entity, $filePath, $fileName);

        $this->storage->cleanUp($filePath . $fileName . ".gv");
    }

    /**
     * @param EntityInterface $entity
     * @param string          $savePath
     * @param string          $saveName
     */
    private function buildNodes(EntityInterface $entity, $savePath, $saveName)
    {
        if (!empty($entity->getNodes())) {
            $this->generateSvgFile($entity, $savePath, $saveName);

            foreach ($entity->getNodes() as $entityNode) {
                if ($entityNode !== null) {
                    $this->buildNodes($entityNode, $savePath, $saveName);
                }
            }
        }
    }

    /**
     * @param EntityInterface $entity
     * @param string          $savePath
     * @param string          $saveName
     */
    private function generateSvgFile(EntityInterface $entity, $savePath, $saveName)
    {
        $this->fileContent = "digraph my_graph {" . PHP_EOL;
        $this->fileContentEdges = "";

        $graphConfig = '';
        foreach ($this->style->getGraphStyle() as $style) {
            $graphConfig .= $style . ";" . PHP_EOL;
        }
        $this->fileContent .= ($graphConfig);

        $currentNode = 'node [ label = "' . $entity->getLabel1() . '|' . $entity->getLabel3() . '"';
        foreach ($this->style->getNodeStyle() as $style) {
            $currentNode .= ", " . $style;
        }
        $currentNode .= ' ] ' . $entity->getId() . ';' . PHP_EOL;
        $this->fileContent .= ($currentNode);

        foreach ($entity->getNodes() as $entityNode) {
            if ($entityNode !== null) {
                $currentNode = 'node [ label = "' . $entityNode->getLabel1() . '|' . $entityNode->getLabel3() . '"';
                if (!empty($entityNode->getNodes())) {
                    $currentNode .= ' , URL="' . $saveName . $entityNode->getId() . '.svg" ';
                }
                foreach ($this->style->getNodeStyle() as $style) {
                    $currentNode .= ", " . $style;
                }
                $currentNode .= ' ] ' . $entityNode->getId() . ';' . PHP_EOL;
                $this->fileContent .= ($currentNode);

                $this->fileContentEdges .= $entity->getId() . ' -> ' . $entityNode->getId() . '[ ';
                foreach ($this->style->getEdgeStyle() as $style) {
                    $this->fileContentEdges .= $style . ' , ';
                }
                $this->fileContentEdges .= ']; ' . PHP_EOL;
            }
        }
        $this->fileContent .= $this->fileContentEdges . '}';

        $this->storage->store($savePath . $saveName . '.gv', $this->fileContent);
        $this->storage->runCommand("circo " . $savePath . $saveName . ".gv -Tsvg -o "
            . $savePath . $saveName . $entity->getId() . ".svg");
    }
}
