<?php

namespace AsyncAws\Iam\Tests\Unit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\Iam\Result\ListServiceSpecificCredentialsResponse;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class ListServiceSpecificCredentialsResponseTest extends TestCase
{
    public function testListServiceSpecificCredentialsResponse(): void
    {
        // see https://docs.aws.amazon.com/IAM/latest/APIReference/API_ListServiceSpecificCredentials.html
        $response = new SimpleMockedResponse('<ListServiceSpecificCredentialsResponse xmlns="https://iam.amazonaws.com/doc/2010-05-08/">
  <ListServiceSpecificCredentialsResult>
    <ServiceSpecificCredentials>
      <member>
        <ServiceName>codecommit.amazonaws.com</ServiceName>
        <UserName>thomas</UserName>
        <ServiceUserName>thomas-at-123456789012</ServiceUserName>
        <ServiceSpecificCredentialId>ACCA12345ABCDEXAMPLE</ServiceSpecificCredentialId>
        <Status>Active</Status>
        <CreateDate>2016-11-01T17:44:54Z</CreateDate>
      </member>
      <member>
        <ServiceName>cognito.amazonaws.com</ServiceName>
        <UserName>thomas</UserName>
        <ServiceUserName>thomas+1-at-123456789012</ServiceUserName>
        <ServiceSpecificCredentialId>ACCA67890FGHIEXAMPLE</ServiceSpecificCredentialId>
        <Status>Active</Status>
        <CreateDate>2016-11-01T18:22:26Z</CreateDate>
      </member>
    </ServiceSpecificCredentials>
  </ListServiceSpecificCredentialsResult>
  <ResponseMetadata>
    <RequestId>EXAMPLE8-90ab-cdef-fedc-ba987EXAMPLE</RequestId>
  </ResponseMetadata>
</ListServiceSpecificCredentialsResponse>');

        $client = new MockHttpClient($response);
        $result = new ListServiceSpecificCredentialsResponse(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));

        self::assertCount(2, $result->getServiceSpecificCredentials());
        self::assertSame('thomas', $result->getServiceSpecificCredentials()[0]->getUserName());
        self::assertSame('thomas', $result->getServiceSpecificCredentials()[1]->getUserName());
        self::assertSame('ACCA12345ABCDEXAMPLE', $result->getServiceSpecificCredentials()[0]->getServiceSpecificCredentialId());
        self::assertSame('ACCA67890FGHIEXAMPLE', $result->getServiceSpecificCredentials()[1]->getServiceSpecificCredentialId());
        self::assertSame('codecommit.amazonaws.com', $result->getServiceSpecificCredentials()[0]->getServiceName());
        self::assertSame('cognito.amazonaws.com', $result->getServiceSpecificCredentials()[1]->getServiceName());
    }
}
