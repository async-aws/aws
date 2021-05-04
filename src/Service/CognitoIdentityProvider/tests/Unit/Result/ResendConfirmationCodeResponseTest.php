<?php

namespace AsyncAws\CognitoIdentityProvider\Tests\Unit\Result;

use AsyncAws\CognitoIdentityProvider\Enum\DeliveryMediumType;
use AsyncAws\CognitoIdentityProvider\Result\ResendConfirmationCodeResponse;
use AsyncAws\CognitoIdentityProvider\ValueObject\CodeDeliveryDetailsType;
use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class ResendConfirmationCodeResponseTest extends TestCase
{
    public function testResendConfirmationCodeResponse(): void
    {
        // see https://docs.aws.amazon.com/cognito-user-identity-pools/latest/APIReference/API_ResendConfirmationCode.html
        $response = new SimpleMockedResponse(json_encode([
            'CodeDeliveryDetails' => [
                'AttributeName' => 'my-attribute',
                'DeliveryMedium' => DeliveryMediumType::EMAIL,
                'Destination' => 'my-destination',
            ],
        ]));

        $client = new MockHttpClient($response);
        $result = new ResendConfirmationCodeResponse(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));

        self::assertEquals(
            CodeDeliveryDetailsType::create(
                [
                    'AttributeName' => 'my-attribute',
                    'DeliveryMedium' => DeliveryMediumType::EMAIL,
                    'Destination' => 'my-destination',
                ]
            ),
            $result->getCodeDeliveryDetails()
        );
    }
}
