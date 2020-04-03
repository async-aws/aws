---
category: features
---

# Pagination

Some API Results are lists of items, like the result of `CloudFormationClient::DescribeStacks()`.
These results implement `\IteratorAggregate` and will automatically use AWS's pagination
API to make a new request to fetch the remaining resources in the list.

```php
use AsyncAws\CloudFormation\CloudFormationClient;
use AsyncAws\CloudFormation\ValueObject\Stack;

$cloudFormation = new CloudFormationClient();

$result = $cloudFormation->describeStacks();

/** @var Stack $stack */
foreach($result as $stack) {
    echo $stack->getStackName();
}
```

These results also provide methods to fetch metadata returned by AWS or
to explicitly access the list of items by using a meaningful method name.

```diff
-foreach($result as $stack) {
+foreach($result->getStacks() as $stack) {
```

If you want to disable pagination, you may call the `getStacks()` function with
boolean `true` as first argument.

```diff
-foreach($result->getStacks() as $stack) {
+foreach($result->getStacks(true) as $stack) {
```

Some endpoints return several lists of items in the same response, like the
result of `S3::ListObjectsV2()`.
When iterating over those results, all lists will be mixed in the yielded items.

```php
use AsyncAws\S3\Input\ListObjectsV2Request;
use AsyncAws\S3\S3Client;
use AsyncAws\S3\ValueObject\AwsObject;
use AsyncAws\S3\ValueObject\CommonPrefix;

$s3 = new S3Client();

$objects = $s3->listObjectsV2(new ListObjectsV2Request([
    'Bucket' => 'my-company-website',
    'Delimiter' => '/'
]));

/** @var AwsObject|CommonPrefix $object */
foreach($objects as $object) {
    if ($object instanceof AwsObject) {
        echo '- '.$object->getKey();
    } else {
        echo 'd '.$object->getPrefix();
    }
}
```
