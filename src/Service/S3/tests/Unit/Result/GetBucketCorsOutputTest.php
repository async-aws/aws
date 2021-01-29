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
        self::markTestSkipped('Generated response don\'t seem to corresponds to reality');

        // see example-1.json from SDK
        $response = new SimpleMockedResponse('<CORSRules>
          <CORSRule>
            <AllowedHeaders>Authorization</AllowedHeaders>
            <AllowedMethods>GET</AllowedMethods>
            <AllowedOrigins>*</AllowedOrigins>
            <MaxAgeSeconds>3000</MaxAgeSeconds>
          </CORSRule>
          <CORSRule>
            <AllowedHeaders>*</AllowedHeaders>
            <AllowedMethods>DELETE</AllowedMethods>
            <AllowedOrigins>example.com</AllowedOrigins>
          </CORSRule>
        </CORSRules>');

        $client = new MockHttpClient($response);
        $result = new GetBucketCorsOutput(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));

        self::assertCount(2, $result->getCORSRules());

        $firstRule = $result->getCORSRules()[0];
//        self::assertSame('GET', $firstRule->);

        // self::assertTODO(expected, $result->getCORSRules());
    }
}
