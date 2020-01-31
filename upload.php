<?php

require __DIR__.'/vendor/autoload.php';

$s3 = new \AsyncAws\S3\S3Client(\Symfony\Component\HttpClient\HttpClient::create(), [
    'region' => 'us-east-1',
    'profile' => 'nyholm',
]);

$s3->putObject(['']);
$s3->putObject([
    'Bucket' => 'bref-example',
    'Key' => 'test.txt',
    'Body' => 'foobar',
    ''
]);