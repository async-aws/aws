---
category: integration
---

# Flysystem integration

The Flysystem integration supports both Flysystem version 1 and version 2.

## Install

```shell
$ composer require async-aws/flysystem-s3
```

## Use

```php
use League\Flysystem\Filesystem;
use AsyncAws\Flysystem\S3\S3FilesystemV2;
use AsyncAws\S3\S3Client;

$adapter = new S3FilesystemV2(new S3Client(), 'bucket');
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
