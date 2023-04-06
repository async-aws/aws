<?php

namespace AsyncAws\Athena\Tests\Unit\Result;

use AsyncAws\Athena\Result\GetNamedQueryOutput;
use AsyncAws\Athena\ValueObject\NamedQuery;
use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class GetNamedQueryOutputTest extends TestCase
{
    public function testGetNamedQueryOutput(): void
    {
        // see https://docs.aws.amazon.com/athena/latest/APIReference/API_GetNamedQuery.html
        $response = new SimpleMockedResponse('{
              "NamedQuery": {
                  "Database": "my_iad_db",
                  "Description": "iad database",
                  "Name": "IadQuery",
                  "NamedQueryId": "my-uid-78555-2563",
                  "QueryString": "Select * from my_table limit 5",
                  "WorkGroup": "iadinternational"
           }
        }');

        $client = new MockHttpClient($response);
        $result = new GetNamedQueryOutput(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));

        self::assertInstanceOf(NamedQuery::class, $result->getNamedQuery());
        self::assertSame('my-uid-78555-2563', $result->getNamedQuery()->getNamedQueryId());
        self::assertSame('IadQuery', $result->getNamedQuery()->getName());
    }
}
