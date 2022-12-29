<?php

namespace AsyncAws\Iam\Tests\Unit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\Iam\Result\CreateServiceSpecificCredentialResponse;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class CreateServiceSpecificCredentialResponseTest extends TestCase
{
    public function testCreateServiceSpecificCredentialResponse(): void
    {
        // see https://docs.aws.amazon.com/IAM/latest/APIReference/API_CreateServiceSpecificCredential.html
        $response = new SimpleMockedResponse('<CreateServiceSpecificCredentialResponse xmlns="https://iam.amazonaws.com/doc/2010-05-08/">
  <CreateServiceSpecificCredentialResult>
    <ServiceSpecificCredential>
      <ServicePassword>xTBAr/czp+D3EXAMPLE47lrJ6/43r2zqGwR3EXAMPLE=</ServicePassword>
      <ServiceName>codecommit.amazonaws.com</ServiceName>
      <UserName>anika</UserName>
      <ServiceUserName>anika+1-at-123456789012</ServiceUserName>
      <ServiceSpecificCredentialId>ACCA12345ABCDEXAMPLE</ServiceSpecificCredentialId>
      <Status>Active</Status>
      <CreateDate>2016-11-01T17:47:22.382Z</CreateDate>
    </ServiceSpecificCredential>
  </CreateServiceSpecificCredentialResult>
  <ResponseMetadata>
    <RequestId>EXAMPLE8-90ab-cdef-fedc-ba987EXAMPLE</RequestId>
  </ResponseMetadata>
</CreateServiceSpecificCredentialResponse>');

        $client = new MockHttpClient($response);
        $result = new CreateServiceSpecificCredentialResponse(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));

        self::assertSame('xTBAr/czp+D3EXAMPLE47lrJ6/43r2zqGwR3EXAMPLE=', $result->getServiceSpecificCredential()->getServicePassword());
        self::assertSame('codecommit.amazonaws.com', $result->getServiceSpecificCredential()->getServiceName());
        self::assertSame('anika', $result->getServiceSpecificCredential()->getUserName());
        self::assertSame('anika+1-at-123456789012', $result->getServiceSpecificCredential()->getServiceUserName());
        self::assertSame('ACCA12345ABCDEXAMPLE', $result->getServiceSpecificCredential()->getServiceSpecificCredentialId());
        self::assertSame('Active', $result->getServiceSpecificCredential()->getStatus());
        self::assertSame('2016-11-01T17:47:22.382Z', $result->getServiceSpecificCredential()->getCreateDate()->format('Y-m-d\TH:i:s.v\Z'));
    }
}
