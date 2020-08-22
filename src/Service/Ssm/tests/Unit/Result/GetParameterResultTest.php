<?php

namespace AsyncAws\Ssm\Tests\Unit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\Ssm\Enum\ParameterType;
use AsyncAws\Ssm\Result\GetParameterResult;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class GetParameterResultTest extends TestCase
{
    public function testGetParameterResult(): void
    {
        // see https://docs.aws.amazon.com/systems-manager/latest/APIReference/API_GetParameter.html
        $response = new SimpleMockedResponse('{
            "Parameter": {
                "ARN": "arn:aws:ssm:us-east-2:111122223333:parameter/MyGitHubPassword",
                "DataType": "text",
                "LastModifiedDate": 1582657288.8,
                "Name": "MyGitHubPassword",
                "Type": "SecureString",
                "Value": "AYA39c3b3042cd2aEXAMPLE/AKIAIOSFODNN7EXAMPLE/fh983hg9awEXAMPLE==",
                "Version": 3
            }
        }');

        $client = new MockHttpClient($response);
        $result = new GetParameterResult(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));

        self::assertSame('arn:aws:ssm:us-east-2:111122223333:parameter/MyGitHubPassword', $result->getParameter()->getARN());
        self::assertSame('20200225 190128 800000', $result->getParameter()->getLastModifiedDate()->format('Ymd His u'));
        self::assertSame('MyGitHubPassword', $result->getParameter()->getName());
        self::assertSame(ParameterType::SECURE_STRING, $result->getParameter()->getType());
        self::assertSame('AYA39c3b3042cd2aEXAMPLE/AKIAIOSFODNN7EXAMPLE/fh983hg9awEXAMPLE==', $result->getParameter()->getValue());
        self::assertSame('3', $result->getParameter()->getVersion());
    }
}
