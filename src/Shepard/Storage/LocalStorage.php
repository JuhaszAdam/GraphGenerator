<?php

namespace Shepard\Storage;

use Symfony\Component\Filesystem\Exception\IOException;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Filesystem\Filesystem;

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
        $fileSystem = new Filesystem();

        try {
            $fileSystem->mkdir($this->basePath, 0755, true);
        } catch (IOExceptionInterface $e) {
            throw new IOException("An error occurred while creating your directory at " . $e->getPath());
        }

        $path = $this->basePath . $path;
        $file = fopen($path, "w");
        fputs($file, $content);
        fclose($file);
    }
}
