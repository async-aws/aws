<?php

namespace AsyncAws\S3\Tests\Unit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\S3\Result\GetBucketCorsOutput;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class GetBucketCorsOutputTest extends TestCase
{
    public function testGetBucketCorsOutput(): void
    {
        $response = new SimpleMockedResponse('
<CORSConfiguration xmlns="http://s3.amazonaws.com/doc/2006-03-01/">
    <CORSRule>
        <AllowedOrigin>*</AllowedOrigin>
        <AllowedMethod>PUT</AllowedMethod>
        <AllowedMethod>POST</AllowedMethod>
        <AllowedMethod>GET</AllowedMethod>
        <AllowedHeader>*</AllowedHeader>
    </CORSRule>
</CORSConfiguration>');

        $client = new MockHttpClient($response);
        $result = new GetBucketCorsOutput(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));

        self::assertCount(1, $result->getCORSRules());

        $firstRule = $result->getCORSRules()[0];
        self::assertCount(3, $firstRule->getAllowedMethods());
        self::assertSame('*', $firstRule->getAllowedOrigins()[0]);
        self::assertCount(0, $firstRule->getExposeHeaders());
        self::assertNull($firstRule->getMaxAgeSeconds());
    }
}
