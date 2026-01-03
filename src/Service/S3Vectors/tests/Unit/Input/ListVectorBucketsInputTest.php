<?php

namespace AsyncAws\S3Vectors\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\S3Vectors\Input\ListVectorBucketsInput;

class ListVectorBucketsInputTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new ListVectorBucketsInput([
            'maxResults' => 50,
            'nextToken' => 'token123',
            'prefix' => 'prod-',
        ]);

        // see https://docs.aws.amazon.com/AmazonS3/latest/API/API_Operations_Amazon_S3_Vectors.html/API_ListVectorBuckets.html
        $expected = '
            POST /ListVectorBuckets HTTP/1.0
            Content-Type: application/json
            Accept: application/json

            {"maxResults":50,"nextToken":"token123","prefix":"prod-"}
        ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
