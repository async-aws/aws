<?php

namespace AsyncAws\CognitoIdentityProvider\Result;

use AsyncAws\CognitoIdentityProvider\ValueObject\CodeDeliveryDetailsType;
use AsyncAws\Core\Response;
use AsyncAws\Core\Result;

class ForgotPasswordResponse extends Result
{
    /**
     * The code delivery details returned by the server in response to the request to reset a password.
     */
    private $CodeDeliveryDetails;

    public function getCodeDeliveryDetails(): ?CodeDeliveryDetailsType
    {
        $this->initialize();

        return $this->CodeDeliveryDetails;
    }

    protected function populateResult(Response $response): void
    {
        $data = $response->toArray();

        $this->CodeDeliveryDetails = empty($data['CodeDeliveryDetails']) ? null : new CodeDeliveryDetailsType([
            'Destination' => isset($data['CodeDeliveryDetails']['Destination']) ? (string) $data['CodeDeliveryDetails']['Destination'] : null,
            'DeliveryMedium' => isset($data['CodeDeliveryDetails']['DeliveryMedium']) ? (string) $data['CodeDeliveryDetails']['DeliveryMedium'] : null,
            'AttributeName' => isset($data['CodeDeliveryDetails']['AttributeName']) ? (string) $data['CodeDeliveryDetails']['AttributeName'] : null,
        ]);
    }
}
