<?php

namespace Shepard\Style;

class Style
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
     * @param array|null $graphStyle
     * @param array|null $nodeStyle
     * @param array|null $edgeStyle
     */
    public function __construct(array $graphStyle = null, array $nodeStyle = null, array $edgeStyle = null)
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
     * @return array
     */
    public function getGraphStyle()
    {
        return $this->graphStyle;
    }

    /**
     * @param array $graphStyle
     */
    public function setGraphStyle(array $graphStyle)
    {
        $this->graphStyle = $graphStyle;
    }

    /**
     * @return array
     */
    public function getNodeStyle()
    {
        return $this->nodeStyle;
    }

    /**
     * @param array $nodeStyle
     */
    public function setNodeStyle(array $nodeStyle)
    {
        $this->nodeStyle = $nodeStyle;
    }

    /**
     * @return array
     */
    public function getEdgeStyle()
    {
        return $this->edgeStyle;
    }

    /**
     * @param array $edgeStyle
     */
    public function setEdgeStyle(array $edgeStyle)
    {
        $this->edgeStyle = $edgeStyle;
    }
}
