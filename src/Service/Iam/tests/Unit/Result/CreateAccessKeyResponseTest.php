<?php

namespace AsyncAws\Iam\Tests\Unit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\Iam\Enum\StatusType;
use AsyncAws\Iam\Result\CreateAccessKeyResponse;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class CreateAccessKeyResponseTest extends TestCase
{
    public function testCreateAccessKeyResponse(): void
    {
        // see example-1.json from SDK
        $response = new SimpleMockedResponse('<CreateAccessKeyResponse xmlns="https://iam.amazonaws.com/doc/2010-05-08/">
          <CreateAccessKeyResult>
            <AccessKey>
              <UserName>Bob</UserName>
              <AccessKeyId>AKIAIOSFODNN7EXAMPLE</AccessKeyId>
              <Status>Active</Status>
              <SecretAccessKey>wJalrXUtnFEMI/K7MDENG/bPxRfiCYzEXAMPLEKEY</SecretAccessKey>
              <CreateDate>2020-10-13T17:38:43Z</CreateDate>
            </AccessKey>
          </CreateAccessKeyResult>
          <ResponseMetadata>
            <RequestId>7a62c49f-347e-4fc4-9331-6e8eEXAMPLE</RequestId>
          </ResponseMetadata>
        </CreateAccessKeyResponse>');

        $client = new MockHttpClient($response);
        $result = new CreateAccessKeyResponse(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));

        self::assertSame('AKIAIOSFODNN7EXAMPLE', $result->getAccessKey()->getAccessKeyId());
        self::assertSame('2020-10-13 17:38:43.000000', $result->getAccessKey()->getCreateDate()->format('Y-m-d H:i:s.u'));
        self::assertSame('wJalrXUtnFEMI/K7MDENG/bPxRfiCYzEXAMPLEKEY', $result->getAccessKey()->getSecretAccessKey());
        self::assertSame(StatusType::ACTIVE, $result->getAccessKey()->getStatus());
        self::assertSame('Bob', $result->getAccessKey()->getUserName());
    }
}
