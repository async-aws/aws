<?php

declare(strict_types=1);

namespace AsyncAws\Illuminate\Filesystem\Tests\Unit;

use AsyncAws\Flysystem\S3\AsyncAwsS3Adapter;
use AsyncAws\Illuminate\Filesystem\ServiceProvider;
use Illuminate\Filesystem\FilesystemAdapter;
use PHPUnit\Framework\TestCase;

class ServiceProviderTest extends TestCase
{
    public function testCreateFilesystem()
    {
        $app = 'app';
        $serviceProvider = new ServiceProvider($app);
        $illuminateFilesystemAdapter = $serviceProvider->createFilesystem($app, [
            'key' => 'my_key',
            'secret' => 'my_secret',
            'bucket' => 'my_bucket',
            'region' => 'ap-southeast-1',
        ]);

        self::assertInstanceOf(FilesystemAdapter::class, $illuminateFilesystemAdapter);
        $flysystemFilesystem = $illuminateFilesystemAdapter->getDriver();
        /** @var AsyncAwsS3Adapter $s3FilesystemAdapter */
        $s3FilesystemAdapter = $flysystemFilesystem->getAdapter();
        $refl = new \ReflectionClass($s3FilesystemAdapter);

        // Verify bucket
        $property = $refl->getProperty('bucket');
        $property->setAccessible(true);
        $bucket = $property->getValue($s3FilesystemAdapter);
        self::assertEquals('my_bucket', $bucket);

        // Verify config
        $client = $s3FilesystemAdapter->getClient();

        $config = $client->getConfiguration();
        self::assertEquals('my_key', $config->get('accessKeyId'));
        self::assertEquals('my_secret', $config->get('accessKeySecret'));
        self::assertEquals('ap-southeast-1', $config->get('region'));
    }
}
