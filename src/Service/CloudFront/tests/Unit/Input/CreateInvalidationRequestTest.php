<?php

namespace AsyncAws\CloudFront\Tests\Unit\Input;

use AsyncAws\CloudFront\Input\CreateInvalidationRequest;
use AsyncAws\CloudFront\ValueObject\InvalidationBatch;
use AsyncAws\CloudFront\ValueObject\Paths;
use AsyncAws\Core\Test\TestCase;

class CreateInvalidationRequestTest extends TestCase
{
    public function testRequest(): void
    {
        self::fail('Not implemented');

                $input = new CreateInvalidationRequest([
                            'DistributionId' => 'change me',
        'InvalidationBatch' => new InvalidationBatch([
                            'Paths' => new Paths([
                            'Quantity' => 1337,
        'Items' => ['change me'],
                        ]),
        'CallerReference' => 'change me',
                        ]),
                        ]);

                // see https://docs.aws.amazon.com/cloudfront/latest/APIReference/API_CreateInvalidation2019_03_26.html
                $expected = '
            POST / HTTP/1.0
            Content-Type: application/xml

            <change>it</change>
                ';

                self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
