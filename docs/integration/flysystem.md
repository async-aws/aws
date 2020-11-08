---
category: integration
---

# Flysystem integration

[Flysystem](https://flysystem.thephpleague.com/v2/docs/) is a great abstraction over
different types of filesystems. Flysystem uses "adapters" internally to communicate
with the actual filesystem implementation. They have adapters for local disk, FTP,
in-memory etc.

This package is a Flysystem adapter for S3 using AsyncAws. The integration supports
only Flysystem version 1. Flysystem version 2 has an [official AsyncAws adapter](https://flysystem.thephpleague.com/v2/docs/adapter/async-aws-s3/).

## Install

```shell
composer require async-aws/flysystem-s3
```

## Use

```php
use League\Flysystem\Filesystem;
use AsyncAws\Flysystem\S3\AsyncAwsS3Adapter;
use AsyncAws\S3\S3Client;

$adapter = new AsyncAwsS3Adapter(new S3Client(), 'bucket');
$filesystem = new Filesystem($adapter);
$resource = tmpfile();

$filesystem->write('dir/path.txt', 'contents');
$filesystem->writeStream('dir/path.txt', $resource);

$filesystem->delete('dir/path.txt');

$filesystem->createDirectory('dir');
$filesystem->deleteDirectory('dir');

$lastModified = $filesystem->lastModified('path.txt');
$mimeType = $filesystem->mimeType('path.txt');
$fileSize = $filesystem->fileSize('path.txt');
```
