# Flysystem adapter for Async AWS S3

![](https://github.com/async-aws/flysystem-s3/workflows/Tests/badge.svg?branch=master)
![](https://github.com/async-aws/flysystem-s3/workflows/BC%20Check/badge.svg?branch=master)

Flysystem S3 adapter. Currently only compatible with Flysystem v2.

## Install

```cli
composer require async-aws/flysystem-s3
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
