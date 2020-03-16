<?php

namespace AsyncAws\S3\Tests\Unit\Result;

use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\S3\Result\GetObjectAclOutput;
use AsyncAws\S3\ValueObject\Grant;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;

class GetObjectAclOutputTest extends TestCase
{
    public function testGetObjectAclOutput(): void
    {
        $headers = [
            'x-amz-id-2' => 'FPd72k25yOfCXzhrrqZPKIjirB9hAiIJUnW69iQ4qxUCj+Zf1u5WRltvzOAQY4Ey0ivkRvzZRKI=',
            'x-amz-request-id' => 'CB0EFE1772CC73F8',
            'date' => 'Sun, 23 Feb 2020 08:57:37 GMT',
            'content-type' => 'application/xml',
            'transfer-encoding' => 'chunked',
            'x-amz-request-charged' => 'requester',
            'server' => 'AmazonS3',
        ];
        $response = new SimpleMockedResponse('<?xml version="1.0" encoding="UTF-8"?>
<AccessControlPolicy xmlns="http://s3.amazonaws.com/doc/2006-03-01/">
<Owner>
    <ID>78830d484ca31cf82348f0168785e7929a89f1409630f003170a6b48addfeb9b</ID>
</Owner>
<AccessControlList>
    <Grant>
        <Grantee xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:type="CanonicalUser">
            <ID>78830d484ca31cf82348f0168785e7929a89f1409630f003170a6b48addfeb9b</ID>
        </Grantee>
        <Permission>FULL_CONTROL</Permission>
    </Grant>
    <Grant>
        <Grantee xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:type="Group">
            <URI>http://acs.amazonaws.com/groups/global/AllUsers</URI>
        </Grantee>
        <Permission>READ</Permission>
    </Grant>
</AccessControlList>
</AccessControlPolicy>', $headers);

        $client = new MockHttpClient($response);
        $result = new GetObjectAclOutput($client->request('POST', 'http://localhost'), $client);

        self::assertEquals('requester', $result->getRequestCharged());
        self::assertEquals('78830d484ca31cf82348f0168785e7929a89f1409630f003170a6b48addfeb9b', $result->getOwner()->getID());
        $grants = $result->getGrants();
        self::assertCount(2, $grants);
        self::assertEquals('FULL_CONTROL', $grants[0]->getPermission());
        self::assertEquals('READ', $grants[1]->getPermission());
    }

    public function testGrantsOutput()
    {
        $response = new SimpleMockedResponse('<?xml version="1.0" encoding="UTF-8"?>
        <AccessControlPolicy xmlns="http://s3.amazonaws.com/doc/2006-03-01/">
            <Owner>
                <ID>abc</ID>
                <DisplayName>You</DisplayName>
            </Owner>
            <AccessControlList>
                <Grant>
                    <Grantee xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:type="CanonicalUser">
                        <ID>abc</ID>
                        <DisplayName>You</DisplayName>
                        </Grantee>
                    <Permission>FULL_CONTROL</Permission>
                </Grant>
                <Grant>
                    <Grantee xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:type="CanonicalUser">
                        <ID>edf</ID>
                        <DisplayName>Me</DisplayName>
                    </Grantee>
                    <Permission>FULL_CONTROL</Permission>
                </Grant>
            </AccessControlList>
        </AccessControlPolicy>
        ');

        $client = new MockHttpClient($response);
        $result = new GetObjectAclOutput($client->request('POST', 'http://localhost'), $client);

        $grants = $result->getGrants();
        self::assertCount(2, $grants);

        foreach ($grants as $grant) {
            self::assertInstanceOf(Grant::class, $grant);
            self::assertEquals('CanonicalUser', $grant->getGrantee()->getType());
        }
    }
}
