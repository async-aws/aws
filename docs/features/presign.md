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

$s3 = new S3Client();
$input = new GetObjectRequest([
    'Bucket' => 'my-bucket',
    'Key' => 'test',
]);

$url = $s3->presign($input, new \DateTimeImmutable('+60 min'));

echo $url;
```

> **Note**: AWS has limitations on the expiration length. Read more in [the docs](https://aws.amazon.com/premiumsupport/knowledge-center/presigned-url-s3-bucket-expiration/).
