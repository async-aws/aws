<?php

declare(strict_types=1);

namespace AsyncAws\Flysystem\S3\Tests\Integration;

use AsyncAws\Core\Configuration;
use AsyncAws\Core\Credentials\NullProvider;
use AsyncAws\Flysystem\S3\AsyncAwsS3Adapter;
use AsyncAws\S3\S3Client;
use League\Flysystem\AdapterInterface;
use League\Flysystem\Config;
use PHPUnit\Framework\TestCase;

class AsyncAwsS3AdapterTest extends TestCase
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
                $adapter->deleteDir($item['path']);
            } else {
                $adapter->delete($item['path']);
            }
        }
    }

    public function testWrite(): void
    {
        $adapter = $this->adapter();
        $adapter->write('file.txt', 'my contents', new Config());

        self::assertTrue($adapter->has('file.txt'));
        self::assertEquals('my contents', $adapter->read('file.txt')['contents']);
    }

    public function testListContents(): void
    {
        if (\PHP_VERSION_ID === 70406) {
            self::markTestSkipped('Skipped because of https://bugs.php.net/bug.php?id=79616');
        }

        $adapter = $this->adapter();
        $adapter->write('file.txt', 'my contents', new Config());
        $adapter->write('foo/file2.txt', '22', new Config());
        $result = $adapter->listContents('foo');

        self::assertCount(2, $result);
        self::assertEquals('file', $result[0]['type']);
        self::assertEquals('foo/file2.txt', $result[0]['path']);
        self::assertEquals('dir', $result[1]['type']);
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
        $bucket = isset($_SERVER['FLYSYSTEM_AWS_S3_BUCKET']) ? $_SERVER['FLYSYSTEM_AWS_S3_BUCKET'] : 'flysystem-bucket-v1';
        $prefix = isset($_SERVER['FLYSYSTEM_AWS_S3_PREFIX']) ? $_SERVER['FLYSYSTEM_AWS_S3_PREFIX'] : static::$adapterPrefix;

        return new AsyncAwsS3Adapter($this->s3Client(), $bucket, $prefix);
    }

    private function s3Client(): S3Client
    {
        if ($this->s3Client instanceof S3Client) {
            return $this->s3Client;
        }

        $key = $_SERVER['FLYSYSTEM_AWS_S3_KEY'] ?? null;
        $secret = $_SERVER['FLYSYSTEM_AWS_S3_SECRET'] ?? null;
        $bucket = $_SERVER['FLYSYSTEM_AWS_S3_BUCKET'] ?? null;
        $region = isset($_SERVER['FLYSYSTEM_AWS_S3_REGION']) ? $_SERVER['FLYSYSTEM_AWS_S3_REGION'] : 'eu-central-1';

        if (!$key || !$secret || !$bucket) {
            self::$docker = true;
            $options = ['endpoint' => 'http://localhost:4570'];
            if (\is_callable([Configuration::class, 'optionExists']) && Configuration::optionExists('pathStyleEndpoint')) {
                $options += ['pathStyleEndpoint' => true];
            }

            return $this->s3Client = new S3Client($options, new NullProvider());
        }

        $options = ['accessKeyId' => $key, 'accessKeySecret' => $secret, 'region' => $region];
        if (\is_callable([Configuration::class, 'optionExists']) && Configuration::optionExists('pathStyleEndpoint')) {
            $options += ['pathStyleEndpoint' => true];
        }

        return $this->s3Client = new S3Client($options);
    }
}
