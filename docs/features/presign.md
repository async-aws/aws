---
category: features
---

# Presign URLs

You may "presign" all requests with the `$client->presign()` function. It will return
a URL that is safe to share to anyone. The URL contains encrypted authentication
parameters.

This feature can be used if you want to give a user access to a file on S3 but only
for a limited time.

```php
use AsyncAws\S3\Input\GetObjectRequest;
use AsyncAws\S3\S3Client;

$s3Client = new S3Client();
$input = new GetObjectRequest([
    'Bucket' => 'my-bucket',
    'Key' => 'test',
]);

$url = $s3Client->presign($input, new \DateTimeImmutable('+60 min'));

echo $url;
```
