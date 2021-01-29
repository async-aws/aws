<?php

namespace AsyncAws\CognitoIdentityProvider\Result;

use AsyncAws\CognitoIdentityProvider\ValueObject\CodeDeliveryDetailsType;
use AsyncAws\Core\Response;
use AsyncAws\Core\Result;

/**
 * The response from the server when the Amazon Cognito Your User Pools service makes the request to resend a
 * confirmation code.
 */
class ResendConfirmationCodeResponse extends Result
{
    /**
     * The code delivery details returned by the server in response to the request to resend the confirmation code.
     */
    private $codeDeliveryDetails;

    public function getCodeDeliveryDetails(): ?CodeDeliveryDetailsType
    {
        $this->initialize();

        return $this->codeDeliveryDetails;
    }

    protected function populateResult(Response $response): void
    {
        $data = $response->toArray();

        $this->codeDeliveryDetails = empty($data['CodeDeliveryDetails']) ? null : new CodeDeliveryDetailsType([
            'Destination' => isset($data['CodeDeliveryDetails']['Destination']) ? (string) $data['CodeDeliveryDetails']['Destination'] : null,
            'DeliveryMedium' => isset($data['CodeDeliveryDetails']['DeliveryMedium']) ? (string) $data['CodeDeliveryDetails']['DeliveryMedium'] : null,
            'AttributeName' => isset($data['CodeDeliveryDetails']['AttributeName']) ? (string) $data['CodeDeliveryDetails']['AttributeName'] : null,
        ]);
    }
}
