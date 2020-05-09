<?php

namespace AsyncAws\Illuminate\Filesystem;

use AsyncAws\Flysystem\S3\S3FilesystemV1;
use AsyncAws\S3\S3Client;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Filesystem\FilesystemManager;
use League\Flysystem\Adapter\AbstractAdapter;

class AsyncAwsFilesystemManager extends FilesystemManager
{
    /**
     * Create an instance of the Amazon S3 driver.
     *
     * @return FilesystemAdapter
     */
    public function createAsyncAwsS3Driver(array $config)
    {
        $s3Config = [];
        if ($config['key'] && $config['secret']) {
            $s3Config['accessKeyId'] = $config['key'] ?? null;
            $s3Config['accessKeySecret'] = $config['secret'] ?? null;
            $s3Config['sessionToken'] = $config['token'] ?? null;
        }

        if (!empty($config['endpoint'])) {
            $s3Config['endpoint'] = $config['endpoint'];
        }

        $root = $config['root'] ?? '';
        $options = $config['options'] ?? [];

        $s3Client = new S3Client($s3Config);
        if (class_exists(AbstractAdapter::class)) {
            $flysystemAdapter = new S3FilesystemV1($s3Client, $config['bucket'], $root, $options);
        } else {
            throw new \RuntimeException('Could not use AsyncAwsS3 since Flysystem v1 is not installed. Run "composer require league/flysystem:^1.0"');
        }

        return new FilesystemAdapter($this->createFlysystem($flysystemAdapter, $config));
    }
}
