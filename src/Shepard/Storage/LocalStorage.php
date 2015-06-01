<?php

namespace Shepard\Storage;

use Symfony\Component\Process\Process;

class LocalStorage implements StorageInterface
{
    /**
     * @var string
     */
    private $basePath;

    /**
     * @param string $basePath
     */
    public function __construct($basePath = "/tmp/")
    {
        $this->basePath = $basePath;
    }

    /**
     * @inheritdoc
     */
    public function storeContent($path, $content)
    {
        if (!is_dir($this->basePath)) {
            (new Process(mkdir($this->basePath, 0755, true)))->run();
        }

        $path = $this->basePath . $path;
        $file = fopen($path, "w");
        fputs($file, $content);
        fclose($file);
    }
}
