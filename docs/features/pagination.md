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
use AsyncAws\S3\Input\ListObjectsV2Request;

$s3 = new S3Client();
$input = new ListObjectsV2Request();
$input->setBucket('my-company-website');

$result = $s3->listObjectsV2($input);

/** @var AwsObject|CommonPrefix $file */
foreach($result as $file) {
    if ($file instanceof AwsObject) {
        echo $file->getKey();
    }
}
```

If you want to disable pagination, you may call the `getContents()` function with
boolean `true` as first argument.

```diff
-foreach($result as $file) {
+foreach($result->getContents(true) as $file) {
```
