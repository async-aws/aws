<?php

declare(strict_types=1);

namespace AsyncAws\Illuminate\Filesystem\Tests\Unit;

use AsyncAws\Flysystem\S3\S3FilesystemV1;
use AsyncAws\Illuminate\Filesystem\ServiceProvider;
use Illuminate\Filesystem\FilesystemAdapter;
use League\Flysystem\Adapter\AbstractAdapter;
use PHPUnit\Framework\TestCase;

class ServiceProviderTest extends TestCase
{
    public static function setUpBeforeClass(): void
    {
        if (!class_exists(AbstractAdapter::class)) {
            self::markTestSkipped('Flysystem v1 is not installed');
        }
    }

    public function testCreateFilesystem()
    {
        $app = 'app';
        $serviceProvider = new ServiceProvider($app);
        $illuminateFilesystemAdapter = $serviceProvider->createFilesystem($app, [
            'key' => 'my_key',
            'secret' => 'my_secret',
            'bucket' => 'my_bucket',
        ]);

        self::assertInstanceOf(FilesystemAdapter::class, $illuminateFilesystemAdapter);
        $flysystemFilesystem = $illuminateFilesystemAdapter->getDriver();
        /** @var S3FilesystemV1 $s3FilesystemAdapter */
        $s3FilesystemAdapter = $flysystemFilesystem->getAdapter();
        $refl = new \ReflectionClass($s3FilesystemAdapter);

        // Verify bucket
        $property = $refl->getProperty('bucket');
        $property->setAccessible(true);
        $bucket = $property->getValue($s3FilesystemAdapter);
        self::assertEquals('my_bucket', $bucket);

        // Verify config
        $property = $refl->getProperty('client');
        $property->setAccessible(true);
        $client = $property->getValue($s3FilesystemAdapter);

        $config = $client->getConfiguration();
        self::assertEquals('my_key', $config->get('accessKeyId'));
        self::assertEquals('my_secret', $config->get('accessKeySecret'));
    }
}
