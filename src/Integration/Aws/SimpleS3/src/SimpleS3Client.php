<?php

declare(strict_types=1);

namespace AsyncAws\SimpleS3;

use AsyncAws\S3\Input\GetObjectRequest;
use AsyncAws\S3\S3Client;

/**
 * A simplified S3 client that hides some of the complexity of working with S3.
 * The aim of this client is to provide shortcut methods to about 80% common tasks.
 *
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
class SimpleS3Client extends S3Client
{
    public function getUrl(string $bucket, string $key): string
    {
        return $this->getEndpoint(sprintf('/%s/%s', $bucket, $key), [], null);
    }

    /*
     * TODO Add get() and put() which supports large files
     * TODO add has()
     */
}
