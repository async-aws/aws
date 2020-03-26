---
category: features
---

# Pagination

Some API Results are lists of items, like the result of `S3Client::listObjectsV2()`.
These results implement `\IteratorAggregate` and will automatically use AWS's pagination
API to make a new request to fetch the remaining resources in the list.

```php
use AsyncAws\S3\S3Client;
use AsyncAws\S3\ValueObject\AwsObject;
use AsyncAws\S3\ValueObject\CommonPrefix;

$s3Client = new S3Client();
$result = $s3Client->listObjectsV2(['Bucket' => 'foo']);

/** @var AwsObject|CommonPrefix $file */
foreach($result as $file) {
    if ($file instanceof AwsObject) {
        echo $file->getKey();
    }
}
```

