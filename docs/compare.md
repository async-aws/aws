---
layout: compare
---

# Compare AsyncAws vs AWS PHP SDK

An overview over how AsyncAws differ from the official AWS PHP SDK

|                                               | AWS PHP SDK | AsyncAws
|-----------------------------------------------|-------------|---------
| [Asynchronous](#asynchronous-experience)      |             | &#x2714;
| [Pagination](#pagination-experience)          |             | &#x2714;
| [Presign](#presign-experience)                |             | &#x2714;
| [Developer experience](#developer-experience) |             | &#x2714;
| [Package size](#package-size)                 |             | &#x2714;
| [Mock and testing](#mock-and-testing)         |             | &#x2714;
| [Features coverage](#features-coverage)       | &#x2714;    |

## Asynchronous experience

All API calls are asynchronous by default. Thanks to the power of the
[Symfony HTTP client](https://symfony.com/doc/current/components/http_client.html)
the process is not blocking until you need to read the result.

### AsyncAws

```php
use AsyncAws\S3\S3Client;

$s3 = new S3Client();
$files = [];
foreach (range(0, 10) as $index) {
    $files[] = $s3->putObject([
        'Bucket' => 'my.bucket',
        'Key' => 'file-' . uniqid('file-', true),
        'Body' => 'test',
    ]);
}

// At this point, calls to putObjects are not yet resolved

foreach ($files as $file) {
    // calling $file->getKey() will wait the response from AWS
    $s3->deleteObject([
        'Bucket' => 'my.bucket',
        'Key' => $file->getKey(),
    ]);
}

// No need to wait ends of deleteObject.
// It will be automatically resolved on destruct.
```

### Official AWS PHP SDK

```php
use Aws\S3\S3Client;
use GuzzleHttp\Promise;

$s3 = new S3Client([]);
$promises = [];
foreach (range(0, 10) as $index) {
    $promises[] = $s3->putObjectAsync([
        'Bucket' => 'my.bucket',
        'Key' => 'file-' . uniqid('file-', true),
        'Body' => 'test',
    ]);
}

$deletePromises = [];
foreach ($promises as $promise) {
    $file = $promise->wait();

    $deletePromises[] = $s3->deleteObjectAsync([
        'Bucket' => 'my.bucket',
        'Key' => $file['Key'],
    ]);
}

Promise\all($deletePromises)->wait();
```

Read more about [how to async works](/features/async.md).

## Pagination experience

AsyncAws handles the complexity of paginated results. You don't
have to worry about `IsTruncated` or `NextMarker` or calling magic methods, just
iterates over results, AsyncAws do the rest.

### AsyncAws

```php
use AsyncAws\S3\S3Client;

$s3 = new S3Client();

$objects = $s3->listObjectsV2(['Bucket' => 'my.bucket']);
foreach ($objects as $object) {
    // ...
}
```

### Official AWS PHP SDK

```php
use Aws\S3\S3Client;

$s3 = new S3Client([]);
$nextToken = null;
do {
    $objects = $s3->ListObjectsV2([
        'Bucket' => 'my-bucket',
        'NextContinuationToken' => $nextToken,
    ]);

    foreach ($objects['Contents'] as $object) {
        // ...
    }
    foreach ($objects['CommonPrefixes'] as $object) {
        // ...
    }
    $nextToken = $objects['ContinuationToken'];
} while ($nextToken !== null);

// or with paginator

$pages = $s3->getPaginator('ListObjectsV2', ['Bucket' => 'my.bucket']);
foreach ($pages as $page) {
    foreach ($page['Contents'] as $object) {
        // ...
    }
    foreach ($page['CommonPrefixes'] as $object) {
        // ...
    }
}

```

Read more about [pagination](/features/pagination.md).

## Presign experience

AWS allow pre-generating sign url that let user access to a resource
without exposing the key. For instance, provide a link to download an S3 Object.
AsyncAws provides a fancy way to generate such url by reusing the same objects
used in the standard way.

### AsyncAws

```php
use AsyncAws\S3\S3Client;
use AsyncAws\S3\Input\GetObjectRequest;

$s3 = new S3Client();
$input = new GetObjectRequest([
    'Bucket' => 'my-bucket',
    'Key' => 'test',
]);

// Sign on the fly
$content = $s3->getObject($input);

// Presign Url
$url = $s3->presign($input, new \DateTimeImmutable('+60 min'));
echo $url;
```

### Official AWS PHP SDK

```php
use Aws\S3\S3Client;

$s3 = new S3Client([]);
// Sign on the fly
$content = $s3->getObject([
    'Bucket' => 'my-bucket',
    'Key' => 'test',
]);

// Presign Url
$command = $s3->getCommand('GetObject', [
    'Bucket' => 'my-bucket',
    'Key' => 'test',
]);
$psr7 = $s3->createPresignedRequest($cmd, '+60 min');
echo (string) $psr7->getUri();

```

> **Note**: While official AWS PHP SDK provide methods to presign S3 methods only,
> AsyncAws let you presign requests for every services.

Read more about [presign feature](/features/presign.md).

## Developer experience

Have you ever got an error like this when using the official AWS PHP SDK?

> PHP Fatal error: Missing required client configuration options: version: (string)

The solution is to dig into the documentation and blindly copy the `version` from the
examples... AsyncAws will pick the correct version of the API for you. No need to
worry about that.

AsyncAws also provides real classes with documented getters and methods, while
the official AWS PHP SDK uses magic methods and undocumented array accessor.

| AWS PHP SDK | AsyncAws |
| ----------- | -------- |
| [![AWS PHP SDK method doc](/assets/image/compare/aws-method.png)](/assets/image/compare/aws-method.png) | [![async-aws method doc](/assets/image/compare/aa-method.png)](/assets/image/compare/aa-method.png)
| [![AWS PHP SDK input doc](/assets/image/compare/aws-input.png)](/assets/image/compare/aws-input.png)    | [![async-aws input doc](/assets/image/compare/aa-input.png)](/assets/image/compare/aa-input.png)
| [![AWS PHP SDK result doc](/assets/image/compare/aws-result.png)](/assets/image/compare/aws-result.png) | [![async-aws result doc](/assets/image/compare/aa-result.png)](/assets/image/compare/aa-result.png)

Like the official AWS PHP SDK, AsyncAws supports multi-regions clients. This
enables users to specify which AWS Region to call by providing an `@region`
input parameter. But AsyncAws don't require to create new objects.

### AsyncAws

```diff
 $lambda = new LambdaClient();
 $result = $lambda->invoke([
   'FunctionName' => 'app-dev-hello_world',
   'Payload' => '{"name": "async-aws/lambda"}',
+  '@region' => 'eu-west-1',
 ]);
```

### Official AWS PHP SDK

```diff
-$lambda = new LambdaClient();
+$lambda = (new \Aws\Sdk)->createMultiRegionLambda(['version' => 'latest']);

 $result = $lambda->invoke([
   'FunctionName' => 'app-dev-hello_world',
   'Payload' => '{"name": "async-aws/lambda"}',
+  '@region' => 'eu-west-1',
 ]);
```

## Package size

By providing isolated package for each service, AsyncAws is ultra thin. For
instance `aws-async/s3` + `aws-async/core` weighs **0.6Mib**, while official AWS
PHP SDK weighs **22MiB** regardless of the number of API services you use.

This has an increased importance if you are using Docker or if you deploy your
applications on AWS Lambda.

## Mock and testing

Because AsyncAws uses real classes, it is easy to mock them in PHPUnit tests.
The official AWS PHP SDK uses the magic `__call()` methods which increase
complexity and reduce the developer experience.

Read more about [writing tests](/features/tests.md).

## Features coverage

While AsyncAws focused on the most used operations (around a dozen services),
The official AWS PHP SDK covers the full scope of AWS (200 services and 8,000
methods).

Read more about what [clients are supported](/clients/index.md).
