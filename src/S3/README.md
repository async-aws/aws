# Async AWS S3 Client

![](https://github.com/async-aws/s3/workflows/Tests/badge.svg?branch=master)
![](https://github.com/async-aws/s3/workflows/BC%20Check/badge.svg?branch=master)

An API client for S3.

## Install

```cli
composer require async-aws/s3
```

## Upload File via Stream

```php
$s3Client = new S3Client(['region' => 'eu-west-1']);

$resource = \fopen('/path/to/big/file', 'r');
$s3Client->PutObject([
    'Bucket' => 'ah-brightcove',
    'Key' => 'file.jpg',
    'Body' => $resource,
]);

// or via Closure
$fp = \fopen('/path/to/big/file', 'r');
$s3Client->PutObject([
    'Bucket' => 'ah-brightcove',
    'Key' => 'file.jpg',
    'ContentLength' => filesize('/path/to/big/file'), // This is important
    'Body' => static function(int $length) use ($fp) {
        return fread($fp, $length);
    },
]);
```

When using a `Closure`, it's important to provide the property `ContentLength`.
This information is required by AWS, and cannot be guessed by AsyncAws.
If `ContentLength` is absent, AsyncAws will read the output into memory before 
sending the request.
