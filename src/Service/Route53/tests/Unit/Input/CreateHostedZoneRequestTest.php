<?php

declare(strict_types=1);

namespace AsyncAws\Route53\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\Route53\Enum\VPCRegion;
use AsyncAws\Route53\Input\CreateHostedZoneRequest;
use AsyncAws\Route53\ValueObject\HostedZoneConfig;
use AsyncAws\Route53\ValueObject\VPC;

class CreateHostedZoneRequestTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new CreateHostedZoneRequest([
            'Name' => 'example.com',
            'VPC' => new VPC([
                'VPCRegion' => VPCRegion::US_EAST_2,
                'VPCId' => 'vpc-1a2b3c4d',
            ]),
            'CallerReference' => 'myUniqueIdentifier',
            'HostedZoneConfig' => new HostedZoneConfig([
                'Comment' => 'This is my first hosted zone.',
                'PrivateZone' => true,
            ]),
            'DelegationSetId' => '/delegationset/N1PA6795SAMPLE',
        ]);

        // see https://docs.aws.amazon.com/Route53/latest/APIReference/API_CreateHostedZone.html
        $expected = '
POST /2013-04-01/hostedzone HTTP/1.1
Content-Type: application/xml

<?xml version="1.0" encoding="UTF-8"?>
<CreateHostedZoneRequest xmlns="https://route53.amazonaws.com/doc/2013-04-01/">
   <Name>example.com</Name>
   <VPC>
      <VPCRegion>us-east-2</VPCRegion>
      <VPCId>vpc-1a2b3c4d</VPCId>
   </VPC>
   <CallerReference>myUniqueIdentifier</CallerReference>
   <HostedZoneConfig>
      <Comment>This is my first hosted zone.</Comment>
      <PrivateZone>true</PrivateZone>
   </HostedZoneConfig>
   <DelegationSetId>/delegationset/N1PA6795SAMPLE</DelegationSetId>
</CreateHostedZoneRequest>
';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
