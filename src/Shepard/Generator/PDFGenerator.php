<?php

namespace Shepard\Generator;

use Doctrine\Instantiator\Exception\InvalidArgumentException;
use Shepard\Entity\EntityInterface;

class PDFGenerator extends AbstractGenerator
{
    /**
     * @var string
     */
    private $fileContent = "";

    /**
     * @var string
     */
    private $fileContentEdgesDot = "";

    /**
     * @param EntityInterface[] $entity
     * @param string            $filePath
     */
    public function draw(array $entity, $filePath = "graph/g")
    {
        if (count($entity) <= 200) {
            $this->fileContent = "digraph my_graph{" . PHP_EOL;
            $this->fileContentEdgesDot = "";
            $graphConfig = '';
            foreach ($this->style->getGraphStyle() as $style) {
                $graphConfig .= $style . ";" . PHP_EOL;
            }
            $this->fileContent .= ($graphConfig);

            $currentNode = 'node [ label = "' . $entity[0]->getLabel1() . '|' . $entity[0]->getLabel3() . '"';
            foreach ($this->style->getNodeStyle() as $style) {
                $currentNode .= ", " . $style;
            }
            $currentNode .= ' ] ' . $entity[0]->getId() . ';' . PHP_EOL;
            $this->fileContent .= ($currentNode);

            $this->buildNodes($entity[0], $filePath);

            $this->fileContent .= $this->fileContentEdgesDot . '}';

            $this->storage->store($filePath . '.gv', $this->fileContent);
            $this->storage->runCommand("circo " . $filePath . ".gv -Tpdf -o " . $filePath . ".pdf");
            $this->storage->cleanUp($filePath . ".gv");
        } else {
            throw new InvalidArgumentException("PDF format cannot handle more than 200 entities . ");
        }
    }

    /**
     * @param EntityInterface $entity
     */
    private function buildNodes(EntityInterface $entity)
    {
        foreach ($entity->getNodes() as $entityNode) {
            if ($entityNode !== null) {
                $currentNode = 'node [ label = "' . $entityNode->getLabel1() . ' | ' . $entityNode->getLabel3() . '"';
                foreach ($this->style->getNodeStyle() as $style) {
                    $currentNode .= ", " . $style;
                }
                $currentNode .= ' ] ' . $entityNode->getId() . ';' . PHP_EOL;
                $this->fileContent .= ($currentNode);

                $this->fileContentEdgesDot .= $entity->getId() . ' -> ' . $entityNode->getId() . '[ ';
                foreach ($this->style->getEdgeStyle() as $style) {
                    $this->fileContentEdgesDot .= $style . ' , ';
                }
                $this->fileContentEdgesDot .= 'len="10.0"]; ' . PHP_EOL;

                $this->buildNodes($entityNode);
            }
        }
    }
}
