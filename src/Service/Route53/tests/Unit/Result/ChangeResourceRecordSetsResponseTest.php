<?php

declare(strict_types=1);

namespace AsyncAws\Route53\Tests\Unit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\Route53\Result\ChangeResourceRecordSetsResponse;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class ChangeResourceRecordSetsResponseTest extends TestCase
{
    public function testChangeResourceRecordSetsResponse(): void
    {
        // see example-1.json from SDK
        $response = new SimpleMockedResponse('<?xml version="1.0" encoding="UTF-8"?>
<ChangeResourceRecordSetsResponse>
    <ChangeInfo>
        <Comment>Web server for example.com</Comment>
        <Id>/change/C2682N5HXP0BZ4</Id>
        <Status>PENDING</Status>
        <SubmittedAt>2017-02-10T01:36:41.958Z</SubmittedAt>
    </ChangeInfo>
</ChangeResourceRecordSetsResponse>');

        $client = new MockHttpClient($response);
        $result = new ChangeResourceRecordSetsResponse(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));

        self::assertSame('/change/C2682N5HXP0BZ4', $result->getChangeInfo()->getId());
        self::assertSame('PENDING', $result->getChangeInfo()->getStatus());
    }
}
