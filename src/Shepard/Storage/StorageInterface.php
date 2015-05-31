<?php

namespace Shepard\Storage;

interface StorageInterface
{
    /**
     * @param string $content
     * @param string $path
     */
    public function storeContent($path, $content);
}
