<?php

namespace AsyncAws\Sso\Tests\Unit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\Sso\Input\ListAccountsRequest;
use AsyncAws\Sso\Result\ListAccountsResponse;
use AsyncAws\Sso\SsoClient;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class ListAccountsResponseTest extends TestCase
{
    public function testListAccountsResponse(): void
    {
        // see https://docs.aws.amazon.com/singlesignon/latest/PortalAPIReference/API_ListAccounts.html
        $response = new SimpleMockedResponse('{
            "nextToken": "eyJuZXh0VG9rZW4iOm51bGx9",
            "accountList": [
                {
                    "accountId": "123456789011",
                    "accountName": "Production",
                    "emailAddress": "aws-prod@example.com"
                },
                {
                    "accountId": "210987654322",
                    "accountName": "Sandbox",
                    "emailAddress": "aws-sandbox@example.com"
                }
            ]
        }');

        $client = new MockHttpClient($response);
        $result = new ListAccountsResponse(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()), new SsoClient(), new ListAccountsRequest([]));

        self::assertSame('eyJuZXh0VG9rZW4iOm51bGx9', $result->getNextToken());

        $accountList = iterator_to_array($result->getAccountList(true));
        self::assertCount(2, $accountList);
        self::assertSame('123456789011', $accountList[0]->getAccountId());
        self::assertSame('Production', $accountList[0]->getAccountName());
        self::assertSame('aws-prod@example.com', $accountList[0]->getEmailAddress());
        self::assertSame('210987654322', $accountList[1]->getAccountId());
        self::assertSame('Sandbox', $accountList[1]->getAccountName());
        self::assertSame('aws-sandbox@example.com', $accountList[1]->getEmailAddress());
    }
}
