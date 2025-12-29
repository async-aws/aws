<?php

namespace AsyncAws\S3Vectors\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\S3Vectors\Input\ListVectorsInput;

class ListVectorsInputTest extends TestCase
{
    public function testRequest(): void
    {
        self::fail('Not implemented');

        $input = new ListVectorsInput([
            'vectorBucketName' => 'change me',
            'indexName' => 'change me',
            'indexArn' => 'change me',
            'maxResults' => 1337,
            'nextToken' => 'change me',
            'segmentCount' => 1337,
            'segmentIndex' => 1337,
            'returnData' => false,
            'returnMetadata' => false,
        ]);

        // see https://docs.aws.amazon.com/AmazonS3/latest/API/API_Operations_Amazon_S3_Vectors.html/API_ListVectors.html
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
