<?php

namespace Shepard\Storage;


class LocalStorage implements StorageInterface
{
    /**
     * @var string
     */
    private $basePath;

    /**
     * @param string $basePath
     */
    public function __construct($basePath = "tmp/")
    {
        $this->basePath = $basePath;
    }

    /**
     * @inheritdoc
     */
    public function storeContent($path, $content)
    {
      //  $path = $this->basePath . $path;
        $file = fopen($path, "w");
        fwrite($file, $content);
        fclose($file);
    }
}
