<?php

namespace AsyncAws\S3\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\S3\Input\PutBucketCorsRequest;
use AsyncAws\S3\ValueObject\CORSConfiguration;
use AsyncAws\S3\ValueObject\CORSRule;

class PutBucketCorsRequestTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new PutBucketCorsRequest([
            'Bucket' => 'bucket-name',
            'CORSConfiguration' => new CORSConfiguration([
                'CORSRules' => [new CORSRule([
                    'AllowedHeaders' => ['*'],
                    'AllowedMethods' => ['POST', 'DELETE'],
                    'AllowedOrigins' => ['*'],
                    'ExposeHeaders' => ['x-amz-server-side-encryption'],
                    'MaxAgeSeconds' => 1337,
                ])],
            ]),
            'ContentMD5' => 'change me',
            'ExpectedBucketOwner' => 'ExpectedBucketOwner',
        ]);

        // see example-1.json from SDK
        $expected = '
PUT /bucket-name?cors HTTP/1.0
Content-Type: application/xml
Content-md5: change me
x-amz-expected-bucket-owner: ExpectedBucketOwner

<?xml version="1.0" encoding="UTF-8"?>
<CORSConfiguration xmlns="http://s3.amazonaws.com/doc/2006-03-01/">
    <CORSRule>
        <AllowedHeader>*</AllowedHeader>
        <AllowedMethod>POST</AllowedMethod>
        <AllowedMethod>DELETE</AllowedMethod>
        <AllowedOrigin>*</AllowedOrigin>
        <ExposeHeader>x-amz-server-side-encryption</ExposeHeader>
        <MaxAgeSeconds>1337</MaxAgeSeconds>
    </CORSRule>
</CORSConfiguration>
';
        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
