<?php

declare(strict_types=1);

namespace AsyncAws\Route53\Tests\Unit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\Route53\Result\DeleteHostedZoneResponse;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class DeleteHostedZoneResponseTest extends TestCase
{
    public function testDeleteHostedZoneResponse(): void
    {
        // see https://docs.aws.amazon.com/route53/latest/APIReference/API_DeleteHostedZone.html
        $response = new SimpleMockedResponse('
        <DeleteHostedZoneResponse xmlns="https://route53.amazonaws.com/doc/2013-04-01/">
           <ChangeInfo>
              <Id>/change/C1PA6795UKMFR9</Id>
              <Status>PENDING</Status>
              <SubmittedAt>2017-03-10T01:36:41.958Z</SubmittedAt>
           </ChangeInfo>
        </DeleteHostedZoneResponse>
        ');

        $client = new MockHttpClient($response);
        $result = new DeleteHostedZoneResponse(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));

        self::assertSame('/change/C1PA6795UKMFR9', $result->getChangeInfo()->getId());
        self::assertSame('PENDING', $result->getChangeInfo()->getStatus());
    }
}
