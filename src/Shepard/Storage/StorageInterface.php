<?php

namespace Shepard\Storage;

interface StorageInterface
{
    /**
     * @param string $content
     * @param string $fileName
     */
    public function storeContent($fileName, $content);
}
