<?php

namespace Shepard\Style;

class Style
{
    /**
     * @var string
     */
    private $graphType;

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
    private $activeNodeStyle = [
        "shape = record",
        "penwidth = 2.0",
        "color = Blue",
        "style = filled",
        "fillcolor = white"
    ];

    /**
     * @var array
     */
    private $edgeStyle = [];

    /**
     * @param string     $graphType
     * @param array|null $graphStyle
     * @param array|null $nodeStyle
     * @param array|null $activeNodeStyle
     * @param array|null $edgeStyle
     */
    public function __construct(
        $graphType,
        array $graphStyle = null,
        array $nodeStyle = null,
        array $activeNodeStyle = null,
        array $edgeStyle = null
    )
    {
        $this->graphType = $graphType;
        if ($graphStyle) {
            $this->graphStyle = $graphStyle;
        }
        if ($nodeStyle) {
            $this->nodeStyle = $nodeStyle;
        }
        if ($activeNodeStyle) {
            $this->activeNodeStyle = $activeNodeStyle;
        }
        if ($edgeStyle) {
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
     * @return array
     */
    public function getNodeStyle()
    {
        return $this->nodeStyle;
    }

    /**
     * @return array
     */
    public function getEdgeStyle()
    {
        return $this->edgeStyle;
    }

    /**
     * @return string
     */
    public function getGraphType()
    {
        return $this->graphType;
    }

    /**
     * @return array
     */
    public function getActiveNodeStyle()
    {
        return $this->activeNodeStyle;
    }
}
