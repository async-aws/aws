---
category: integration
---

# Simple S3 client

An abstraction layer above the S3Client that provides simpler functions to common
tasks. The client will automatically switch to multipart upload for large files.

## Install

```shell
composer require async-aws/simple-s3
```

## Usage

```php
use AsyncAws\SimpleS3\SimpleS3Client;

$s3 = new SimpleS3Client();

$s3->createBucket([
    'Bucket' => 'my-image-bucket',
    'CreateBucketConfiguration' => [
        'LocationConstraint' => 'eu-central-1'
        //  'LocationConstraint' => $s3->getConfiguration()->get(Configuration::OPTION_REGION)
    ],
]);

$resource = \fopen('/path/to/cat/image.jpg', 'r');
$s3->upload('my-image-bucket', 'photos/cat_2.jpg', $resource);
$s3->upload('my-image-bucket', 'photos/cat_2.txt', 'I like this cat');

// Copy objects between buckets
$s3->copy('source-bucket', 'source-key', 'destination-bucket', 'destination-key');

// Check if a file exists
$s3->has('my-image-bucket', 'photos/cat_2.jpg'); // true

// Get file URL
$url = $s3->getUrl('my-image-bucket', 'photos/cat_2.jpg');
echo $url; // https://my-image-bucket.s3.eu-central-1.amazonaws.com/photos/cat_2.jpg

// Download a file
$resource = $s3->download('my-image-bucket', 'photos/cat_2.jpg')->getContentAsResource();
$text = $s3->download('my-image-bucket', 'photos/cat_2.txt')->getContentAsString();
echo $text; // I like this cat
```
