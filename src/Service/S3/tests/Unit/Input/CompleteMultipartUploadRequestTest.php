<?php

namespace AsyncAws\S3\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\S3\Input\CompleteMultipartUploadRequest;
use AsyncAws\S3\ValueObject\CompletedMultipartUpload;
use AsyncAws\S3\ValueObject\CompletedPart;

class CompleteMultipartUploadRequestTest extends TestCase
{
    /**
     * @see https://docs.aws.amazon.com/AmazonS3/latest/API/API_CompleteMultipartUpload.html#API_CompleteMultipartUpload_Examples
     */
    public function testRequest(): void
    {
        $input = new CompleteMultipartUploadRequest([
            'Bucket' => 'example-bucket',
            'Key' => 'example-object',
            'MultipartUpload' => new CompletedMultipartUpload([
                'Parts' => [
                    new CompletedPart(['ETag' => '"a54357aff0632cce46d942af68356b38"', 'PartNumber' => 1]),
                    new CompletedPart(['ETag' => '"0c78aef83f66abc1fa1e8477f296d394"', 'PartNumber' => 2]),
                    new CompletedPart(['ETag' => '"acbd18db4cc2f85cedef654fccc4a4d8"', 'PartNumber' => 3]),
                ],
            ]),
            'UploadId' => 'AAAsb2FkIElEIGZvciBlbHZpbmcncyWeeS1tb3ZpZS5tMnRzIRRwbG9hZA',
        ]);

        // see example-1.json from SDK
        $expected = '
POST /example-bucket/example-object?uploadId=AAAsb2FkIElEIGZvciBlbHZpbmcncyWeeS1tb3ZpZS5tMnRzIRRwbG9hZA HTTP/1.1
Content-Type: application/xml

<CompleteMultipartUpload xmlns="http://s3.amazonaws.com/doc/2006-03-01/">
 <Part>
    <ETag>"a54357aff0632cce46d942af68356b38"</ETag>
    <PartNumber>1</PartNumber>
 </Part>
 <Part>
   <ETag>"0c78aef83f66abc1fa1e8477f296d394"</ETag>
   <PartNumber>2</PartNumber>
 </Part>
 <Part>
   <ETag>"acbd18db4cc2f85cedef654fccc4a4d8"</ETag>
   <PartNumber>3</PartNumber>
 </Part>
</CompleteMultipartUpload>
';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
