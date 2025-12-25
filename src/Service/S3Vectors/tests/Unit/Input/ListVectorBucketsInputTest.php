<?php

namespace AsyncAws\S3Vectors\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\S3Vectors\Input\ListVectorBucketsInput;

class ListVectorBucketsInputTest extends TestCase
{
    public function testRequest(): void
    {
        self::fail('Not implemented');

        $input = new ListVectorBucketsInput([
            'maxResults' => 1337,
            'nextToken' => 'change me',
            'prefix' => 'change me',
        ]);

        // see https://docs.aws.amazon.com/AmazonS3/latest/API/API_Operations_Amazon_S3_Vectors.html/API_ListVectorBuckets.html
        $expected = '
            POST / HTTP/1.0
            Content-Type: application/json

            {
            "change": "it"
        }
                ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
