<?php

namespace AsyncAws\CognitoIdentityProvider\Result;

use AsyncAws\CognitoIdentityProvider\ValueObject\CodeDeliveryDetailsType;
use AsyncAws\Core\Response;
use AsyncAws\Core\Result;

/**
 * The response from the server when Amazon Cognito makes the request to resend a confirmation code.
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

        $this->codeDeliveryDetails = empty($data['CodeDeliveryDetails']) ? null : $this->populateResultCodeDeliveryDetailsType($data['CodeDeliveryDetails']);
    }

    private function populateResultCodeDeliveryDetailsType(array $json): CodeDeliveryDetailsType
    {
        return new CodeDeliveryDetailsType([
            'Destination' => isset($json['Destination']) ? (string) $json['Destination'] : null,
            'DeliveryMedium' => isset($json['DeliveryMedium']) ? (string) $json['DeliveryMedium'] : null,
            'AttributeName' => isset($json['AttributeName']) ? (string) $json['AttributeName'] : null,
        ]);
    }
}
