<?php

namespace Shepard\Tests\Storage;

use Shepard\Storage\StorageInterface;

class ExampleStorage implements StorageInterface
{
    /**
     * @inheritdoc
     */
    public function storeContent($path, $content)
    {
        $file = fopen($path, "w");
        fwrite($file, $content);
        fclose($file);
    }
}
