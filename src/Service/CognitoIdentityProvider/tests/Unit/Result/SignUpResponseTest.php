<?php

namespace AsyncAws\CognitoIdentityProvider\Tests\Unit\Result;

use AsyncAws\CognitoIdentityProvider\Result\SignUpResponse;
use AsyncAws\CognitoIdentityProvider\ValueObject\CodeDeliveryDetailsType;
use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class SignUpResponseTest extends TestCase
{
    public function testSignUpResponse(): void
    {
        // see https://docs.aws.amazon.com/cognitoidentityprovider/latest/APIReference/API_SignUp.html
        $response = new SimpleMockedResponse(\json_encode([
            'CodeDeliveryDetails' => [
                'AttributeName' => 'a',
                'DeliveryMedium' => 'b',
                'Destination' => 'c',
            ],
            'UserConfirmed' => false,
            'UserSub' => 'sub90',
        ]));

        $client = new MockHttpClient($response);
        $result = new SignUpResponse(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));

        self::assertFalse($result->getUserConfirmed());
        self::assertSame('sub90', $result->getUserSub());
        self::assertEquals(
            CodeDeliveryDetailsType::create([
                'AttributeName' => 'a',
                'DeliveryMedium' => 'b',
                'Destination' => 'c',
            ]),
            $result->getCodeDeliveryDetails()
        );
    }
}
