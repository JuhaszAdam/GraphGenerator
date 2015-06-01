<?php

namespace Shepard\Storage;

use Aws\S3\S3Client;

class S3Storage implements StorageInterface
{
    /**
     * @var string
     */
    private $bucket;

    /**
     * @var string
     */
    private $keyname;

    /**
     * @var S3Client $client
     */
    private $client;

    /**
     * @param S3Client $client
     * @param string   $bucket
     * @param string   $keyname
     */
    public function __construct(
        S3Client $client,
        $bucket = "",
        $keyname = ""
    )
    {
        $this->client = $client;
        $this->bucket = $bucket;
        $this->keyname = $keyname;
    }

    /**
     * @inheritdoc
     */
    public function storeContent($path, $content)
    {
        $this->client->upload($this->bucket, $this->keyname, $content);
    }
}
