<?php

namespace Shepard\Generator;

use Shepard\Entity\EntityInterface;
use Fhaculty\Graph\Graph;
use Graphp\GraphViz\GraphViz;
use Fhaculty\Graph\Vertex;
use Symfony\Component\Process\Process;

class GraphGenerator
{
    /**
     * @var array
     *
     * Config the default node visuals here
     *
     * ATTRIBUTE GUIDE:
     * http://www.graphviz.org/doc/info/attrs.html#h:undir_note
     */
    private $configDefaultNodes = [
        "graphviz.shape" => "record",
        "graphviz.color" => "#444444",
        "graphviz.fontcolor" => "#FFFFFF",
        "graphviz.fillcolor" => "#2E2E2E",
        "graphviz.fontname" => "arial",
        "graphviz.width" => 3,
        "graphviz.style" => "bold, filled, rounded",
        "graphviz.splines" => "ortho"
    ];

    /**
     * @var array
     *
     * Config the default edge visuals here
     */
    private $configDefaultEdge = [
        "graphviz.color" => "black",
        "graphviz.dir" => "none",
        "graphviz.overlap" => false,
        "graphviz.style" => "bold",
        "graphviz.splines" => "ortho"
    ];

    /**
     * @var array
     *
     * Config the default node visuals here
     */
    private $configDefaultGraph = [
        "graphviz.bgcolor" => "transparent",
        "graphviz.concentrate" => true,
        "graphviz.splines" => "ortho"
    ];

    /**
     * @var array
     */
    private $graphStyle = ['rankdir=LR', 'size="8,5"', 'bgcolor="white"'];

    /**
     * @var array
     */
    private $nodeStyle = ["shape = record", "penwidth = 2.0", "color = Black", "style = filled", "fillcolor = white"];

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
     * @var string
     */
    private $fileContentGexf = "";

    /**
     * @var string
     */
    private $fileContentEdgesGexf = "";

    /**
     * @var string
     */
    private $filePath = "graph.gv";

    /**
     * @var int
     */
    private $nodeId = 0;

    /**
     * @var int
     */
    private $edgeId = 0;

    /**
     * @param array|null $graphStyle
     * @param array|null $nodeStyle
     * @param array|null $edgeStyle
     * @param string|null $filePath
     */
    public function __construct($graphStyle = null, $nodeStyle = null, $edgeStyle = null, $filePath = null)
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
        if ($filePath !== null) {
            $this->filePath = $filePath;
        }
    }

    /**
     * @return string
     */
    public function getFilePath()
    {
        return $this->filePath;
    }

    /**
     * @param string $filePath
     */
    public function setFilePath($filePath)
    {
        $this->filePath = $filePath;
    }

    /**
     * @return array
     */
    public function getConfigDefaultGraph()
    {
        return $this->configDefaultGraph;
    }

    /**
     * @param array $configDefaultGraph
     */
    public function setConfigDefaultGraph($configDefaultGraph)
    {
        $this->configDefaultGraph = $configDefaultGraph;
    }

    /**
     * @return array
     */
    public function getConfigDefaultEdge()
    {
        return $this->configDefaultEdge;
    }

    /**
     * @param array $configDefaultEdge
     */
    public function setConfigDefaultEdge($configDefaultEdge)
    {
        $this->configDefaultEdge = $configDefaultEdge;
    }

    /**
     * @return array
     */
    public function getConfigDefaultNodes()
    {
        return $this->configDefaultNodes;
    }

    /**
     * @param array $configDefaultNodes
     */
    public function setConfigDefaultNodes($configDefaultNodes)
    {
        $this->configDefaultNodes = $configDefaultNodes;
    }

    /**
     * @param string $username
     * @param string $telephone
     * @param string $email
     * @return string
     */
    private function buildLabel($username, $telephone, $email)
    {
        return "{" . $username . "|" . $telephone . "|" . $email . "}";
    }

    /**
     * @param EntityInterface $entity
     * @param string $savePath
     * @param string $saveName
     * @returns bool
     */
    public function drawSvg($entity, $savePath = "graph/", $saveName = "id")
    {
        $this->checkNodeSvg($entity, $savePath, $saveName);
        (new Process("rm " . $savePath . $saveName . ".gv"))->run();

        return true;
    }

    /**
     * @param EntityInterface $entity
     * @param string $savePath
     * @param string $saveName
     */
    private function checkNodeSvg($entity, $savePath, $saveName)
    {
        if (!empty($entity->getNodes())) {
            $this->generateFileSvg($entity, $savePath, $saveName);

            foreach ($entity->getNodes() as $entityNode) {
                if ($entityNode !== null) {
                    $this->checkNodeSvg($entityNode, $savePath, $saveName);
                }
            }
        }
    }

    /**
     * @param EntityInterface $entity
     * @param string $savePath
     * @param string $saveName
     */
    private function generateFileSvg($entity, $savePath, $saveName)
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

    /**
     * @param EntityInterface[] $entity
     * @param string $savePath
     * @param string $saveName
     * @returns bool
     */
    public function drawPdf(array $entity, $savePath = "graph/", $saveName = "g")
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

            $this->checkNodesPdf($entity[0], $savePath, $saveName);

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
    private function checkNodesPdf($entity)
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

                $this->checkNodesPdf($entityNode);
            }
        }
    }

    /**
     * @param EntityInterface $entity
     * @param string $fileformat
     * @param string $exe
     * @returns bool
     */
    public function draw($entity, $fileformat, $exe)
    {
        $graph = $this->setUpGraph($entity);
        $graph->getAttributeBag()->setAttributes($this->configDefaultGraph);

        $graphviz = new GraphViz();
        $graphviz->setExecutable($exe);
        $graphviz->setFormat($fileformat);

        $graphviz->display($graph);

        return true;
    }

    /**
     * @param EntityInterface $entity
     */
    public function setUpGraphGexf($entity)
    {
        $this->fileContentGexf .= '<node id="' . $this->nodeId++ . '" label="' . $entity->getLabel1()
            . PHP_EOL . $entity->getLabel3() . '" />' . PHP_EOL;

        $this->checkNodeGexf($entity);

        $this->fileContentGexf .= '</nodes>' . PHP_EOL . '<edges>' . PHP_EOL
            . $this->fileContentEdgesGexf . PHP_EOL . '</edges></graph></gexf>';
    }

    /**
     * @param EntityInterface $entity
     */
    private function checkNodeGexf($entity)
    {
        $nodes = $entity->getNodes();
        foreach ($nodes as $entityNode) {
            if ($entityNode !== null) {
                $this->fileContentGexf .= '<node id="' . $this->nodeId++ . '" label="' . $entityNode->getLabel1()
                    . PHP_EOL . $entityNode->getLabel3() . '"/>' . PHP_EOL;

                $this->fileContentEdgesGexf .= '<edge id="' . $this->edgeId++ . '" source="'
                    . $entity->getId() . '" target="' . $entityNode->getId() . '" />' . PHP_EOL;

                $this->checkNodeGexf($entityNode);
            }
        }
    }

    /**
     * @param EntityInterface $entity
     * @return Graph
     */
    public function setUpGraph($entity)
    {
        $graph = new Graph();
        $root = $graph->createVertex($this->nodeId++);
        $root->getAttributeBag()->setAttributes($this->configDefaultNodes);
        $root->setAttribute("graphviz.label", $this->buildLabel(
            $entity->getLabel1(),
            $entity->getLabel2(),
            $entity->getLabel3()));

        $this->checkNode($graph, $root, $entity);

        return $graph;
    }

    /**
     * @param Graph $graph
     * @param Vertex $root
     * @param EntityInterface $entity
     */
    private function checkNode($graph, $root, $entity)
    {
        $nodes = $entity->getNodes();
        foreach ($nodes as $entityNode) {
            if ($entityNode !== null) {
                $node = $graph->createVertex($this->nodeId++);
                $node->getAttributeBag()->setAttributes($this->configDefaultNodes);
                $node->setAttribute("graphviz.group", $entity->getLabel1());
                $node->setAttribute("graphviz.label", $this->buildLabel(
                    $entityNode->getLabel1(),
                    $entityNode->getLabel2(),
                    $entityNode->getLabel3()));

                $edge1 = $root->createEdgeTo($node);
                $edge1->getAttributeBag()->setAttributes($this->configDefaultEdge);

                $this->checkNode($graph, $node, $entityNode);
            }
        }
    }

    /**
     * @param EntityInterface $entity
     * @param string $savePath
     * @returns bool
     */
    public function writeGexfFile($entity, $savePath = "graph.gexf")
    {
        $this->fileContentGexf = '<?' . 'xml version="1.0" encoding="UTF-8"?>
        <gexf xmlns="http://www.gexf.net/1.2draft" version="1.2">
        <meta lastmodifieddate="2009-03-20">
        <creator>Gexf.net</creator>
        <description>Graph</description>
        </meta>
        <graph mode="static" defaultedgetype="directed">
        <nodes>' . PHP_EOL;

        $this->setUpGraphGexf($entity);

        $gvFile = fopen($savePath, "w");
        fwrite($gvFile, $this->fileContentGexf);
        fclose($gvFile);

        return true;
    }
}
