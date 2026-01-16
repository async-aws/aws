<?php

namespace AsyncAws\S3\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\S3\Input\PutBucketVersioningRequest;
use AsyncAws\S3\ValueObject\VersioningConfiguration;

class PutBucketVersioningRequestTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new PutBucketVersioningRequest([
            'Bucket' => 'bucket',
            'VersioningConfiguration' => new VersioningConfiguration([
                'Status' => 'Enabled',
            ]),
        ]);

        // see example-1.json from SDK
        $expected = '
            PUT /bucket?versioning HTTP/1.1
            Content-Type: application/xml

            <VersioningConfiguration xmlns="http://s3.amazonaws.com/doc/2006-03-01/">
              <Status>Enabled</Status>
            </VersioningConfiguration>
        ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
