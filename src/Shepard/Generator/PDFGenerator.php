<?php

namespace Shepard\Generator;

use Doctrine\Instantiator\Exception\InvalidArgumentException;
use Shepard\Entity\EntityInterface;
use Symfony\Component\Process\Process;

class PDFGenerator extends AbstractGenerator
{
    /**
     * @param EntityInterface[] $entity
     * @param string            $fileName
     */
    public function draw(array $entity, $fileName = "g")
    {
        if (count($entity) <= 500) {
            $fileContent = "digraph my_graph{" . PHP_EOL;
            $fileContentEdges = "";
            $graphConfig = '';
            foreach ($this->style->getGraphStyle() as $style) {
                $graphConfig .= $style . ";" . PHP_EOL;
            }
            $fileContent .= ($graphConfig);

            $currentNode = 'node [ label = "{ {' . $entity[0]->getLabel1() . '}|{' . $entity[0]->getLabel2() . '} }"';
            foreach ($this->style->getNodeStyle() as $style) {
                $currentNode .= ", " . $style;
            }
            $currentNode .= ' ] ' . $entity[0]->getId() . ';' . PHP_EOL;
            $fileContent .= ($currentNode);

            $this->buildNodes($entity[0], $fileContent, $fileContentEdges);

            $fileContent .= $fileContentEdges . '}';

            $this->store($fileName, $fileContent);
        } else {
            throw new InvalidArgumentException("PDF format cannot handle more than 200 entities . ");
        }
    }

    /**
     * @param EntityInterface $entity
     * @param string          $fileContent
     * @param string          $fileContentEdges
     */
    private function buildNodes(EntityInterface $entity, &$fileContent, &$fileContentEdges)
    {
        foreach ($entity->getNodes() as $entityNode) {
            if ($entityNode !== null) {
                $currentNode = 'node [ label = "{ {' . $entityNode->getLabel1() . '}|{' . $entityNode->getLabel2() . '} }"';
                foreach ($this->style->getNodeStyle() as $style) {
                    $currentNode .= ", " . $style;
                }
                $currentNode .= ' ] ' . $entityNode->getId() . ';' . PHP_EOL;
                $fileContent .= ($currentNode);

                $fileContentEdges .= $entity->getId() . ' -> ' . $entityNode->getId() . '[ ';
                foreach ($this->style->getEdgeStyle() as $style) {
                    $fileContentEdges .= $style . ' , ';
                }
                $fileContentEdges .= 'len="10.0"]; ' . PHP_EOL;

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
        fputs($tempFile, $fileContent);

        (new Process($this->style->getGraphType() . " " . $path . " -Tpdf -o " . $fileName . ".pdf"))->run();
        fclose($tempFile);

        $this->storage->storeContent($fileName . ".pdf", file_get_contents($fileName . ".pdf"));

        (new Process("rm " . $fileName . ".pdf"))->run();
    }
}
