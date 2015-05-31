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
     * @param string $bucket
     * @param string $keyname
     */
    public function __construct($bucket = "",
                                $keyname = "")
    {
        $this->bucket = $bucket;
        $this->keyname = $keyname;
    }

    /**
     * @inheritdoc
     */
    public function storeContent($path, $content)
    {
        $s3Storage = S3Client::factory();

        $s3Storage->putObject([
            'Bucket'       => $this->bucket,
            'Key'          => $this->keyname,
            'SourceFile'   => $path,
            'ContentType'  => 'text/plain',
            'ACL'          => 'public-read',
            'StorageClass' => 'REDUCED_REDUNDANCY',
            'Metadata'     => [
                'content' => $content
            ]
        ]);
    }
}
