<?php

namespace Shepard\Tests\Storage;

use Shepard\Storage\StorageInterface;
use Symfony\Component\Process\Process;

class ExampleStorage implements StorageInterface
{
    /**
     * @inheritdoc
     */
    public function store($path, $content)
    {
        $file = fopen($path, "w");
        fwrite($file, $content);
        fclose($file);
    }

    /**
     * @inheritdoc
     */
    public function runCommand($command)
    {
        (new Process($command))->run();
    }

    /**
     * @inheritdoc
     */
    public function cleanUp($path)
    {
        (new Process("rm " . $path))->run();
    }
}
