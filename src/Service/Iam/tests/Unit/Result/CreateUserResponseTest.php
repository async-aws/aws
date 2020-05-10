<?php

namespace AsyncAws\Iam\Tests\Unit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\Iam\Result\CreateUserResponse;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class CreateUserResponseTest extends TestCase
{
    public function testCreateUserResponse(): void
    {
        // see example-1.json from SDK
        $response = new SimpleMockedResponse('<CreateUserResponse xmlns="https://iam.amazonaws.com/doc/2010-05-08/">
          <CreateUserResult>
            <User>
              <Arn>arn:aws:iam::123456789012:user/Bob</Arn>
              <CreateDate>2013-06-08T03:20:41.270Z</CreateDate>
              <Path>/</Path>
              <UserId>AKIAIOSFODNN7EXAMPLE</UserId>
              <UserName>Bob</UserName>
            </User>
          </CreateUserResult>
          <ResponseMetadata>
            <RequestId>7a62c49f-347e-4fc4-9331-6e8eEXAMPLE</RequestId>
          </ResponseMetadata>
        </CreateUserResponse>');

        $client = new MockHttpClient($response);
        $result = new CreateUserResponse(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));

        self::assertSame('arn:aws:iam::123456789012:user/Bob', $result->getUser()->getArn());
        self::assertSame('/', $result->getUser()->getPath());
        self::assertSame('AKIAIOSFODNN7EXAMPLE', $result->getUser()->getUserId());
        self::assertSame('Bob', $result->getUser()->getUserName());
        self::assertSame('2013-06-08 03:20:41.270000', $result->getUser()->getCreateDate()->format('Y-m-d H:i:s.u'));
    }
}
