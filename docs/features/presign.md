---
category: features
---

# Presign

```php
use AsyncAws\S3\Input\GetObjectRequest;
use AsyncAws\S3\S3Client;

$s3Client = new S3Client();
$input = new GetObjectRequest(['Bucket' => 'my-bucket', 'Key' => 'test']);

$url = $s3Client->presign($input, new \DateTimeImmutable('+60 min'));

echo $url;
```
