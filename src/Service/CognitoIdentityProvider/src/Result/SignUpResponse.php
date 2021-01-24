<?php

namespace AsyncAws\CognitoIdentityProvider\Result;

use AsyncAws\CognitoIdentityProvider\ValueObject\CodeDeliveryDetailsType;
use AsyncAws\Core\Response;
use AsyncAws\Core\Result;

/**
 * The response from the server for a registration request.
 */
class SignUpResponse extends Result
{
    /**
     * A response from the server indicating that a user registration has been confirmed.
     */
    private $UserConfirmed;

    /**
     * The code delivery details returned by the server response to the user registration request.
     */
    private $CodeDeliveryDetails;

    /**
     * The UUID of the authenticated user. This is not the same as `username`.
     */
    private $UserSub;

    public function getCodeDeliveryDetails(): ?CodeDeliveryDetailsType
    {
        $this->initialize();

        return $this->CodeDeliveryDetails;
    }

    public function getUserConfirmed(): bool
    {
        $this->initialize();

        return $this->UserConfirmed;
    }

    public function getUserSub(): string
    {
        $this->initialize();

        return $this->UserSub;
    }

    protected function populateResult(Response $response): void
    {
        $data = $response->toArray();

        $this->UserConfirmed = filter_var($data['UserConfirmed'], \FILTER_VALIDATE_BOOLEAN);
        $this->CodeDeliveryDetails = empty($data['CodeDeliveryDetails']) ? null : new CodeDeliveryDetailsType([
            'Destination' => isset($data['CodeDeliveryDetails']['Destination']) ? (string) $data['CodeDeliveryDetails']['Destination'] : null,
            'DeliveryMedium' => isset($data['CodeDeliveryDetails']['DeliveryMedium']) ? (string) $data['CodeDeliveryDetails']['DeliveryMedium'] : null,
            'AttributeName' => isset($data['CodeDeliveryDetails']['AttributeName']) ? (string) $data['CodeDeliveryDetails']['AttributeName'] : null,
        ]);
        $this->UserSub = (string) $data['UserSub'];
    }
}
