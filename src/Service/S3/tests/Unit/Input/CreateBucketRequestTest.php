<?php

declare(strict_types=1);

namespace AsyncAws\S3\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\S3\Input\CreateBucketRequest;

class CreateBucketRequestTest extends TestCase
{
    public function testRequest()
    {
        $input = CreateBucketRequest::create(
            [
                'CreateBucketConfiguration' => [
                    'LocationConstraint' => 'Europe',
                ],
            ]
        );

        $expected = '
            PUT / HTTP/1.0
            Content-Type: application/xml

            <CreateBucketConfiguration xmlns="http://s3.amazonaws.com/doc/2006-03-01/">
               <LocationConstraint>Europe</LocationConstraint>
            </CreateBucketConfiguration>
        ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
