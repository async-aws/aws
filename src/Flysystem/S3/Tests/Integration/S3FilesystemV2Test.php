<?php

declare(strict_types=1);

namespace AsyncAws\Flysystem\S3\Tests;

use AsyncAws\Core\Credentials\NullProvider;
use AsyncAws\Flysystem\S3\S3FilesystemV2;
use AsyncAws\S3\S3Client;
use League\Flysystem\Config;
use League\Flysystem\FileAttributes;
use League\Flysystem\FilesystemAdapter;
use League\Flysystem\FilesystemAdapterTestCase;
use League\Flysystem\StorageAttributes;

class S3FilesystemV2Test extends FilesystemAdapterTestCase
{
    private $shouldCleanUp = false;

    private static $adapterPrefix = 'test-prefix';

    /**
     * @var S3Client
     */
    private $s3Client;

    public static function setUpBeforeClass(): void
    {
        static::$adapterPrefix = 'travis-ci/' . bin2hex(random_bytes(10));

        if (!getenv('FLYSYSTEM_AWS_S3_KEY')) {
            // Fixme, Docker is not working
            self::markTestSkipped('Docker image is not working.');
        }
    }

    protected function tearDown(): void
    {
        if (!$this->shouldCleanUp) {
            return;
        }

        $adapter = $this->adapter();
        /** @var StorageAttributes[] $listing */
        $listing = $adapter->listContents('', false);

        foreach ($listing as $item) {
            if ($item->isFile()) {
                $adapter->delete($item->path());
            } else {
                $adapter->deleteDirectory($item->path());
            }
        }
    }

    public function testWriting_with_a_specific_mime_type()
    {
        $adapter = $this->adapter();
        $adapter->write('some/path.txt', 'contents', new Config(['ContentType' => 'text/plain+special']));
        $mimeType = $adapter->mimeType('some/path.txt')->mimeType();
        self::assertEquals('text/plain+special', $mimeType);
    }

    public function testListing_contents_recursive(): void
    {
        $adapter = $this->adapter();
        $adapter->write('something/0/here.txt', 'contents', new Config());
        $adapter->write('something/1/also/here.txt', 'contents', new Config());

        $contents = iterator_to_array($adapter->listContents('', true));

        self::assertCount(2, $contents);
        self::assertContainsOnlyInstancesOf(FileAttributes::class, $contents);
        /** @var FileAttributes $file */
        $file = $contents[0];
        self::assertEquals('something/0/here.txt', $file->path());
        /** @var FileAttributes $file */
        $file = $contents[1];
        self::assertEquals('something/1/also/here.txt', $file->path());
    }

    protected function createFilesystemAdapter(): FilesystemAdapter
    {
        $bucket = getenv('FLYSYSTEM_AWS_S3_BUCKET') ?: 'flysystem-test-bucket';
        $prefix = getenv('FLYSYSTEM_AWS_S3_PREFIX') ?: static::$adapterPrefix;

        return new S3FilesystemV2($this->s3Client(), $bucket, $prefix);
    }

    private function s3Client(): S3Client
    {
        if ($this->s3Client instanceof S3Client) {
            return $this->s3Client;
        }

        $key = getenv('FLYSYSTEM_AWS_S3_KEY');
        $secret = getenv('FLYSYSTEM_AWS_S3_SECRET');
        $bucket = getenv('FLYSYSTEM_AWS_S3_BUCKET');
        $region = getenv('FLYSYSTEM_AWS_S3_REGION') ?: 'eu-central-1';

        if (!$key || !$secret || !$bucket) {
            // Use docker
            $this->shouldCleanUp = true;

            return $this->s3Client = new S3Client(['endpoint' => 'http://localhost:4569'], new NullProvider());
        }

        $this->shouldCleanUp = true;
        $options = ['accessKeyId' => $key, 'accessKeySecret' => $secret, 'region' => $region];

        return $this->s3Client = new S3Client($options);
    }
}
