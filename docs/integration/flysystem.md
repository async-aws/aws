---
category: integration
---

# Flysystem integration

[Flysystem](https://flysystem.thephpleague.com/v2/docs/) is a great abstraction over
different types of filesystems. Flysystem uses "adapters" internally to communicate
with the actual filesystem implementation. They have adapters for local disk, FTP,
in-memory etc.

This package is a Flysystem adapter for S3 using AsyncAws. The integration supports
both Flysystem version 1 and version 2.

## Install

```shell
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

## Large file uploads

AWS S3 has a limit of how large files you can upload. No files can be larger than
5GB. If you need to upload larger files you must use the much more complex "MultipartUpload".

Using Flysystem V2 with [Simple S3](./simple-s3.md) will practically increase this
limit to 640GB.
