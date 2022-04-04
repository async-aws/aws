<?php

namespace AsyncAws\CognitoIdentityProvider\Result;

use AsyncAws\CognitoIdentityProvider\ValueObject\CodeDeliveryDetailsType;
use AsyncAws\Core\Response;
use AsyncAws\Core\Result;

/**
 * Respresents the response from the server regarding the request to reset a password.
 */
class ForgotPasswordResponse extends Result
{
    /**
     * The code delivery details returned by the server in response to the request to reset a password.
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
