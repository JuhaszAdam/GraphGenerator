<?php

namespace Shepard\Storage;

interface StorageInterface
{
    /**
     * @param string $content
     * @param string $path
     */
    public function store($path, $content);

    /**
     * @param string $command
     */
    public function runCommand($command);

    /**
     * @param string $path
     */
    public function cleanUp($path);
}
