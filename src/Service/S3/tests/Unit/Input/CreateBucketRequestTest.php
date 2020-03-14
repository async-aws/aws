<?php

declare(strict_types=1);

namespace AsyncAws\S3\Tests\Unit\Input;

use AsyncAws\S3\Input\CreateBucketRequest;
use PHPUnit\Framework\TestCase;

class CreateBucketRequestTest extends TestCase
{
    public function testRequestBody()
    {
        $input = CreateBucketRequest::create(
            [
                'CreateBucketConfiguration' => [
                    'LocationConstraint' => 'Europe',
                ],
            ]
        );

        $expected = '
            <CreateBucketConfiguration xmlns="http://s3.amazonaws.com/doc/2006-03-01/">
               <LocationConstraint>Europe</LocationConstraint>
            </CreateBucketConfiguration>
        ';

        self::assertXmlStringEqualsXmlString($expected, $input->request()->getBody()->stringify());
    }
}
