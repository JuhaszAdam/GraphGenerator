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
        'rankdir=TB',
        'size="8,5"',
        'bgcolor="white"',
        'labelloc = "top"',
        'labeljust = "left"',
        'fontsize=25'
    ];

    /**
     * @var array
     */
    private $nodeStyle = [
        "shape = record",
        "penwidth = 2.0",
        "color = black",
        "style = filled",
        "fillcolor = white"
    ];

    /**
     * @var array
     */
    private $activeNodeStyle = [
        "shape = record",
        "penwidth = 2.0",
        "color = blue",
        "style = filled",
        "fillcolor = white"
    ];

    /**
     * @var array
     */
    private $mainNodeStyle = [
        "shape = record",
        "penwidth = 2.0",
        'color = red',
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
     * @param array|null $mainNodeStyle
     * @param array|null $edgeStyle
     */
    public function __construct(
        $graphType,
        array $graphStyle = null,
        array $nodeStyle = null,
        array $activeNodeStyle = null,
        array $mainNodeStyle = null,
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
        if ($mainNodeStyle) {
            $this->mainNodeStyle = $mainNodeStyle;
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

    /**
     * @return array
     */
    public function getMainNodeStyle()
    {
        return $this->mainNodeStyle;
    }
}
