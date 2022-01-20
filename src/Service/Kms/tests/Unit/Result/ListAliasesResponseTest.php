<?php

namespace AsyncAws\Kms\Tests\Unit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\Kms\Input\ListAliasesRequest;
use AsyncAws\Kms\KmsClient;
use AsyncAws\Kms\Result\ListAliasesResponse;
use AsyncAws\Kms\ValueObject\AliasListEntry;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class ListAliasesResponseTest extends TestCase
{
    public function testListAliasesResponse(): void
    {
        $response = new SimpleMockedResponse('{
            "Aliases": [
                {
                    "AliasArn": "arn:aws:kms:us-east-2:111122223333:alias\\/aws\\/acm",
                    "AliasName": "alias\\/aws\\/acm",
                    "TargetKeyId": "da03f6f7-d279-427a-9cae-de48d07e5b66"
                },
                {
                    "AliasArn": "arn:aws:kms:us-east-2:111122223333:alias\\/aws\\/ebs",
                    "AliasName": "alias\\/aws\\/ebs",
                    "TargetKeyId": "25a217e7-7170-4b8c-8bf6-045ea5f70e5b"
                },
                {
                    "AliasArn": "arn:aws:kms:us-east-2:111122223333:alias\\/aws\\/rds",
                    "AliasName": "alias\\/aws\\/rds",
                    "TargetKeyId": "7ec3104e-c3f2-4b5c-bf42-bfc4772c6685"
                },
                {
                    "AliasArn": "arn:aws:kms:us-east-2:111122223333:alias\\/aws\\/redshift",
                    "AliasName": "alias\\/aws\\/redshift",
                    "TargetKeyId": "08f7a25a-69e2-4fb5-8f10-393db27326fa"
                },
                {
                    "AliasArn": "arn:aws:kms:us-east-2:111122223333:alias\\/aws\\/s3",
                    "AliasName": "alias\\/aws\\/s3",
                    "TargetKeyId": "d2b0f1a3-580d-4f79-b836-bc983be8cfa5"
                },
                {
                    "AliasArn": "arn:aws:kms:us-east-2:111122223333:alias\\/example1",
                    "AliasName": "alias\\/example1",
                    "TargetKeyId": "4da1e216-62d0-46c5-a7c0-5f3a3d2f8046"
                },
                {
                    "AliasArn": "arn:aws:kms:us-east-2:111122223333:alias\\/example2",
                    "AliasName": "alias\\/example2",
                    "TargetKeyId": "f32fef59-2cc2-445b-8573-2d73328acbee"
                },
                {
                    "AliasArn": "arn:aws:kms:us-east-2:111122223333:alias\\/example3",
                    "AliasName": "alias\\/example3",
                    "TargetKeyId": "1374ef38-d34e-4d5f-b2c9-4e0daee38855"
                }
            ],
            "Truncated": false
        }');

        $client = new MockHttpClient($response);
        $result = new ListAliasesResponse(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()), new KmsClient(), new ListAliasesRequest([]));

        self::assertNull($result->getNextMarker());
        self::assertFalse($result->getTruncated());

        $aliases = iterator_to_array($result->getAliases(true));

        self::assertCount(8, $aliases);
        self::assertInstanceOf(AliasListEntry::class, $aliases[0]);
        self::assertSame('arn:aws:kms:us-east-2:111122223333:alias/aws/acm', $aliases[0]->getAliasArn());
        self::assertSame('alias/aws/acm', $aliases[0]->getAliasName());
        self::assertSame('da03f6f7-d279-427a-9cae-de48d07e5b66', $aliases[0]->getTargetKeyId());
    }
}
