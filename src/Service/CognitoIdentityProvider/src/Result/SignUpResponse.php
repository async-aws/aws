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
     *
     * @var bool
     */
    private $userConfirmed;

    /**
     * The code delivery details returned by the server response to the user registration request.
     *
     * @var CodeDeliveryDetailsType|null
     */
    private $codeDeliveryDetails;

    /**
     * The 128-bit ID of the authenticated user. This isn't the same as `username`.
     *
     * @var string
     */
    private $userSub;

    /**
     * A session Id that you can pass to `ConfirmSignUp` when you want to immediately sign in your user with the `USER_AUTH`
     * flow after they complete sign-up.
     *
     * @var string|null
     */
    private $session;

    public function getCodeDeliveryDetails(): ?CodeDeliveryDetailsType
    {
        $this->initialize();

        return $this->codeDeliveryDetails;
    }

    public function getSession(): ?string
    {
        $this->initialize();

        return $this->session;
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
        $this->codeDeliveryDetails = empty($data['CodeDeliveryDetails']) ? null : $this->populateResultCodeDeliveryDetailsType($data['CodeDeliveryDetails']);
        $this->userSub = (string) $data['UserSub'];
        $this->session = isset($data['Session']) ? (string) $data['Session'] : null;
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
