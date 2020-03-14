<?php

declare(strict_types=1);

namespace AsyncAws\Flysystem\S3\Tests\Integration;

use AsyncAws\Core\Credentials\NullProvider;
use AsyncAws\Flysystem\S3\S3FilesystemV1;
use AsyncAws\S3\S3Client;
use League\Flysystem\AdapterInterface;
use League\Flysystem\Config;
use PHPUnit\Framework\TestCase;

class S3FilesystemV1Test extends TestCase
{
    /**
     * @var AdapterInterface
     */
    private $adapter;

    private static $docker = false;

    /**
     * @var string
     */
    private static $adapterPrefix = 'test-prefix';

    /**
     * @var S3Client
     */
    private $s3Client;

    public static function setUpBeforeClass(): void
    {
        if (!interface_exists(AdapterInterface::class)) {
            self::markTestSkipped('Flysystem v1 is not installed');
        }

        static::$adapterPrefix = 'ci/' . bin2hex(random_bytes(10));
    }

    public function tearDown(): void
    {
        try {
            $adapter = $this->adapter();
        } catch (\Throwable $exception) {
            /**
             * Setting up the filesystem adapter failed. This is OK at this stage.
             * The exception will have been shown to the user when trying to run
             * a test. We expect an exception to be thrown when tests are marked as
             * skipped when a filesystem adapter cannot be constructed.
             */
            return;
        }

        foreach ($adapter->listContents('', false) as $item) {
            if ('dir' === $item['type']) {
                $adapter->deleteDirectory($item['path']);
            } else {
                $adapter->delete($item['path']);
            }
        }
    }

    public function testCopyFile(): void
    {
        $adapter = $this->adapter();
        $adapter->write('source.txt', 'contents to be copied', new Config());
        $adapter->copy('source.txt', 'destination.txt');

        self::assertTrue($adapter->has('source.txt'));
        self::assertTrue($adapter->has('destination.txt'));
        self::assertEquals('contents to be copied', $adapter->read('destination.txt'));
    }

    public function testMoveFile(): void
    {
        $adapter = $this->adapter();
        $adapter->write('source.txt', 'contents to be copied', new Config());
        $adapter->rename('source.txt', 'destination.txt');

        self::assertFalse($adapter->has('source.txt'), 'After moving a file should no longer exist in the original location.');
        self::assertTrue($adapter->has('destination.txt'), 'After moving, a file should be present at the new location.');
        self::assertEquals('contents to be copied', $adapter->read('destination.txt'));
    }

    public function adapter(): AdapterInterface
    {
        if (!$this->adapter instanceof AdapterInterface) {
            $this->adapter = $this->createFilesystemAdapter();
        }

        return $this->adapter;
    }

    protected function createFilesystemAdapter(): AdapterInterface
    {
        $bucket = getenv('FLYSYSTEM_AWS_S3_BUCKET') ?: 'flysystem-bucket-v1';
        $prefix = getenv('FLYSYSTEM_AWS_S3_PREFIX') ?: static::$adapterPrefix;

        return new S3FilesystemV1($this->s3Client(), $bucket, $prefix);
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
            self::$docker = true;

            return $this->s3Client = new S3Client(['endpoint' => 'http://localhost:4569'], new NullProvider());
        }

        $options = ['accessKeyId' => $key, 'accessKeySecret' => $secret, 'region' => $region];

        return $this->s3Client = new S3Client($options);
    }
}
