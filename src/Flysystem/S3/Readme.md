# Flysystem v2 client for Async AWS S3


```
composer requireasync-aws/flysystem-v2-s3
```

```php
<?php

use League\Flysystem\Filesystem;
use AsyncAws\Flysystem\S3Filesystem;
use AsyncAws\S3\S3Client;
use AsyncAws\Flysystem\S3Filesystem;

$adapter = new S3Filesystem(new S3Client(), 'bucket');
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
