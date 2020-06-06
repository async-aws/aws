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
        $input = new CreateInvalidationRequest([
            'DistributionId' => 'DistributionId',
            'InvalidationBatch' => new InvalidationBatch([
                'Paths' => new Paths([
                    'Quantity' => 1337,
                    'Items' => ['all/images'],
                ]),
                'CallerReference' => 'abc',
            ]),
        ]);

        $expected = '
POST /2019-03-26/distribution/DistributionId/invalidation HTTP/1.1
Content-Type: application/xml

<?xml version="1.0" encoding="UTF-8"?>
<InvalidationBatch xmlns="http://cloudfront.amazonaws.com/doc/2019-03-26/">
   <Paths>
      <Quantity>1337</Quantity>
      <Items>
         <Path>all/images</Path>
      </Items>
   </Paths>
   <CallerReference>abc</CallerReference>
</InvalidationBatch>
                ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
