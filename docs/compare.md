# Compare AsyncAws vs AWS PHP SDK

An overview over how AsyncAws differ from the official AWS PHP SDK

|   | AWS PHP SDK | AsyncAws |
|---|-------------|-----------|
| [Async](#async-experience)                    | | <i class="fa fa-check"></i> |
| [Pagination](#pagination-experience)          | | <i class="fa fa-check"></i> |
| [Presign](#presign-experience)                | | <i class="fa fa-check"></i> |
| [Weight](#dependencies-size)                  | | <i class="fa fa-check"></i> |
| [Developer Experience](#developer-experience) | | <i class="fa fa-check"></i> |
| [Mock / Proxy](#mock-and-proxy)               | | <i class="fa fa-check"></i> |
| [Features Coverage](#features-coverage)       | <i class="fa fa-check"></i> | |


## Async eXperience

By default, all calls are async. Thanks to the power of the
[Symfony HTTP client](https://symfony.com/doc/current/components/http_client.html)
the process is not blocking until you need to read the result.

**AsyncAws:**
```php
$files = [];
foreach (range(0, 10) as $index) {
    $files[] = $s3Client->putObject(['Bucket' => 'my.bucket', 'Key' => 'file-' . uniqid('file-', true), 'Body' => 'test']);
}

// at this point, calls to putObjects are not yet resolved

foreach ($files as $file) {
    // calling $file->getKey() will wait the response from AWS and returned the real value
    $s3Client->deleteObject(['Bucket' => 'my.bucket', 'Key' => $file->getKey()]);
}

// no need to wait ends of deleteObject. It will be automatically resolved on destruct
```

**Official AWS PHP SDK:**
```php
use GuzzleHttp\Promise;

$promises = [];
foreach (range(0, 10) as $index) {
    $promises[] = $s3Client->putObjectAsync(['Bucket' => 'my.bucket', 'Key' => 'file-' . uniqid('file-', true), 'Body' => 'test']);
}

$deletePromises = [];
foreach ($promises as $promise) {
    $file = $promise->wait();

    $deletePromises[] = $s3Client->deleteObjectAsync(['Bucket' => 'my.bucket', 'Key' => $file['Key']]);
}
Promise\all($deletePromises)->wait();
```

## Pagination eXperience

AsyncAws handles the complexity of paginated results. You don't
have to worry about `IsTruncated` or `NextMarker` or calling magic methods, just
iterates over results, AsyncAws do the rest.

**AsyncAws:**
```php
$objects = $s3Client->listObjectsV2(['Bucket' => 'my.bucket']);
foreach ($objects as $object) {
    //
}
```

**Official AWS PHP SDK:**
```php
$nextToken = null;
do {
    $objects = $s3Client->ListObjectsV2(['Bucket' => 'my-bucket', 'NextContinuationToken' => $nextToken]);
    foreach ($objects['Contents'] as $object) {
        //
    }
    foreach ($objects['CommonPrefixes'] as $object) {
        //
    }
    $nextToken = $objects['ContinuationToken'];
} while ($nextToken !== null);

// or with paginator

$pages = $s3Client->getPaginator('ListObjectsV2', ['Bucket' => 'my.bucket']);
foreach ($pages as $page) {
    foreach ($page['Contents'] as $object) {
        //
    }
    foreach ($page['CommonPrefixes'] as $object) {
        //
    }
}

```

*note*: Even if pagination is automatically handled, AsyncAws let you fetch
only result for the current page.

## Presign eXperience

AWS allow pre-generating sign url that let user access to a resource
without exposing the key. For instance, provide a link to Download a S3 Object.
AsycAWS provides a fancy way to generate such url by reusing the same objects
used in the standard way.

**AsyncAws:**
```php
$input = new GetObjectRequest(['Bucket' => 'my-bucket', 'Key' => 'test']);

// Sign on the fly
$content = $s3Client->getObject($input);

// Presign Url
$url = $s3Client->presign($input, new \Datetime('+60 min'));
echo $url;
```

**Official AWS PHP SDK:**
```php

// Sign on the fly
$content = $s3Client->getObject(['Bucket' => 'my-bucket', 'Key' => 'test']);

// Presign Url
$command = $s3Client->getCommand('GetObject', ['Bucket' => 'my-bucket', 'Key' => 'test']);
$psr7 = $s3Client->createPresignedRequest($cmd, '+60 min');
echo (string) $psr7->getUri();

```

*Note*: While official AWS PHP SDK provide methods to presign S3 methods only,
AsyncAws let you presign requests for every services.

## Developer eXperience

Ever get the error `PHP Fatal error: Missing required client configuration
options: version: (string)` in official AWS PHP SDK, and dig into documentation
to blindly copy/paste `['version' => '2006-03-01']`? AsyncAws saved you from
this complexity and use the right version for you.

AsyncAws also provides real classes with documented getter and methods, while
the official AWS PHP SDK uses magic methods and undocumented array accessor.

| AWS PHP SDK | AsyncAws |
| ----------- | -------- |
| [![AWS PHP SDK method doc](/assets/image/compare/aws-method.png)](/assets/image/compare/aws-method.png) | [![async-aws method doc](/assets/image/compare/aa-method.png)](/assets/image/compare/aa-method.png)
| [![AWS PHP SDK input doc](/assets/image/compare/aws-input.png)](/assets/image/compare/aws-input.png)    | [![async-aws input doc](/assets/image/compare/aa-input.png)](/assets/image/compare/aa-input.png)
| [![AWS PHP SDK result doc](/assets/image/compare/aws-result.png)](/assets/image/compare/aws-result.png) | [![async-aws result doc](/assets/image/compare/aa-result.png)](/assets/image/compare/aa-result.png)

## Dependencies size

By providing isolated package for each service, AsyncAws is ultra thin. For
instance `aws-async/s3` + `aws-async/core` weighs **0.6Mib**, while official AWS
PHP SDK uses **22MiB** regardless of the number of services you use.

## Mock and Proxy

Because AsyncAws uses real classes, it is easy to Mock them in PHPUnit tests.
The official AWS PHP SDK uses the magic `__call` methods which increase
complexity and reduce the developer experience.

## Features coverage

While AsyncAws focused on the most used operations (around 7 services),
The official AWS PHP SDK covers the full scope of AWS (200 services and 8,000
methods).
