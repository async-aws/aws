<?php

declare(strict_types=1);

namespace AsyncAws\Illuminate\Filesystem;

use AsyncAws\Flysystem\S3\S3FilesystemV1;
use AsyncAws\S3\Input\GetObjectRequest;
use AsyncAws\SimpleS3\SimpleS3Client;
use Illuminate\Filesystem\FilesystemAdapter;

/**
 * A small class that override the url() function.
 *
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
class AsyncAwsFilesystemAdapter extends FilesystemAdapter
{
    /**
     * Return a presigned URL to the S3 object.
     *
     * @param string             $path
     * @param \DateTimeInterface $expiration
     *
     * @return string
     *
     * @throws \RuntimeException
     */
    public function temporaryUrl($path, $expiration, array $options = [])
    {
        if ($expiration instanceof \DateTime) {
            $expiration = \DateTimeImmutable::createFromMutable($expiration);
        }

        if (!$expiration instanceof \DateTimeImmutable) {
            throw new \LogicException('Second argument to AsyncAwsFilesystemAdapter::temporaryUrl() must be a \DateTimeInterface');
        }

        $adapter = $this->getFlysystemAdapter();
        $input = new GetObjectRequest([
            'Bucket' => $adapter->getBucket(),
            'Key' => $adapter->applyPathPrefix($path),
        ]);

        return $adapter->getClient()->presign($input, $expiration);
    }

    /**
     * Get the URL for the file at the given path.
     *
     * @param string $path
     *
     * @return string
     *
     * @throws \RuntimeException
     */
    public function url($path)
    {
        $adapter = $this->getFlysystemAdapter();
        $s3Client = $adapter->getClient();

        if ($s3Client instanceof SimpleS3Client) {
            return $s3Client->getUrl($adapter->getBucket(), $adapter->applyPathPrefix($path));
        }

        // Fallback to get a presigned URL and remove query parameters.
        $url = $this->temporaryUrl($path, new \DateTimeImmutable('+10minutes'));
        if (false === $pos = strpos($url, '?')) {
            throw new \RuntimeException('Expected presigned URL to include a query string');
        }

        return substr($url, 0, $pos);
    }

    private function getFlysystemAdapter(): S3FilesystemV1
    {
        if (!method_exists($this->driver, 'getAdapter')) {
            throw new \RuntimeException(sprintf('Could not call "getAdapter" of class "%s"', \get_class($this->driver)));
        }

        $adapter = $this->driver->getAdapter();

        // Try to unwrap CacheAdapter etc.
        while (!$adapter instanceof S3FilesystemV1 && method_exists($adapter, 'getAdapter')) {
            $adapter = $adapter->getAdapter();
        }

        if (!$adapter instanceof S3FilesystemV1) {
            throw new \LogicException('Expected $adapter to be a S3FilesystemV1');
        }

        return $adapter;
    }
}
