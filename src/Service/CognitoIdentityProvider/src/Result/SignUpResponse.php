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
    private $userConfirmed;

    /**
     * The code delivery details returned by the server response to the user registration request.
     */
    private $codeDeliveryDetails;

    /**
     * The UUID of the authenticated user. This isn't the same as `username`.
     */
    private $userSub;

    public function getCodeDeliveryDetails(): ?CodeDeliveryDetailsType
    {
        $this->initialize();

        return $this->codeDeliveryDetails;
    }

    public function getUserConfirmed(): bool
    {
        $this->initialize();

        return $this->userConfirmed;
    }

    public function getUserSub(): string
    {
        $this->initialize();

        return $this->userSub;
    }

    protected function populateResult(Response $response): void
    {
        $data = $response->toArray();

        $this->userConfirmed = filter_var($data['UserConfirmed'], \FILTER_VALIDATE_BOOLEAN);
        $this->codeDeliveryDetails = empty($data['CodeDeliveryDetails']) ? null : new CodeDeliveryDetailsType([
            'Destination' => isset($data['CodeDeliveryDetails']['Destination']) ? (string) $data['CodeDeliveryDetails']['Destination'] : null,
            'DeliveryMedium' => isset($data['CodeDeliveryDetails']['DeliveryMedium']) ? (string) $data['CodeDeliveryDetails']['DeliveryMedium'] : null,
            'AttributeName' => isset($data['CodeDeliveryDetails']['AttributeName']) ? (string) $data['CodeDeliveryDetails']['AttributeName'] : null,
        ]);
        $this->userSub = (string) $data['UserSub'];
    }
}
