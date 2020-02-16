<?php

declare(strict_types=1);

namespace AsyncAws\S3\Tests\Unit\Result;

use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\S3\Result\GetObjectAclOutput;
use AsyncAws\S3\Result\Grant;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;

class GetObjectAclOutputTest extends TestCase
{
    public function testGrantsOutput()
    {
        $response = new SimpleMockedResponse(<<<XML
<?xml version="1.0" encoding="UTF-8"?>
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
XML
);

        $result = new GetObjectAclOutput($response, new MockHttpClient());

        $grants = $result->getGrants();
        self::assertCount(2, $grants);

        foreach ($grants as $grant) {
            self::assertInstanceOf(Grant::class, $grant);
            self::assertEquals('CanonicalUser', $grant->getGrantee()->getType());
        }
    }
}
