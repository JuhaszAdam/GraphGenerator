<?php

namespace Shepard\Generator;

use Shepard\Entity\EntityInterface;
use Symfony\Component\Process\Process;

class SVGGenerator extends AbstractGenerator
{
    /**
     * @param EntityInterface $entity
     * @param string          $fileName
     * @param int             $level
     */
    public function draw(EntityInterface $entity, $fileName = "id", $level = 0)
    {
        if (!empty($entity->getNodes())) {
            $this->generateSvgFile($entity, $fileName, $level);

            $level++;
            foreach ($entity->getNodes() as $entityNode) {
                if ($entityNode !== null && !empty($entityNode->getNodes())) {
                    $this->generateSvgFile($entityNode, $fileName, $level);
                }
            }

            foreach ($entity->getNodes() as $entityNode) {
                if ($entityNode !== null) {
                    $this->draw($entityNode, $fileName, $level);
                }
            }
        }
    }

    /**
     * @param EntityInterface $entity
     * @param string          $fileName
     * @param int             $level
     */
    private function generateSvgFile(EntityInterface $entity, $fileName, $level)
    {
        $fileContent = "digraph my_graph {" . PHP_EOL;
        $fileContentEdges = "";

        $graphConfig = '';
        foreach ($this->style->getGraphStyle() as $style) {
            $graphConfig .= $style . ";" . PHP_EOL;
        }
        $graphConfig .= 'label = " ' . $entity->getLabel1() . PHP_EOL . 'Level ' . $level . '";';


        $fileContent .= $graphConfig;

        $currentNode = 'node [ label = "{ {' . $entity->getLabel1() . '}|{' . $entity->getLabel3() . '} }"';

        foreach ($this->style->getMainNodeStyle() as $style) {
            $currentNode .= ", " . $style;
        }
        $currentNode .= ' , URL="" ';

        $currentNode .= ' ] ' . $entity->getId() . ';' . PHP_EOL;
        $fileContent .= $currentNode;

        foreach ($entity->getNodes() as $entityNode) {
            if ($entityNode !== null) {
                $currentNode = 'node [ label = "{ {' . $entityNode->getLabel1() . '}|{' . $entityNode->getLabel3() . '} }"';
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

        $this->store(($fileName . $entity->getId()), $fileContent);
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

        (new Process($this->style->getGraphType() . " " . $path . " -Tsvg -o " . $fileName . ".svg"))->run();
        fclose($tempFile);

        $this->storage->storeContent($fileName . ".svg", file_get_contents($fileName . ".svg"));

        (new Process("rm " . $fileName . ".svg"))->run();
    }
}
