<?php

namespace AsyncAws\CognitoIdentityProvider\Result;

use AsyncAws\CognitoIdentityProvider\Enum\DeliveryMediumType;
use AsyncAws\CognitoIdentityProvider\ValueObject\CodeDeliveryDetailsType;
use AsyncAws\Core\Response;
use AsyncAws\Core\Result;

/**
 * The response from the server for a registration request.
 */
class SignUpResponse extends Result
{
    /**
     * Indicates whether the user was automatically confirmed. You can auto-confirm users with a pre sign-up Lambda trigger
     * [^1].
     *
     * [^1]: https://docs.aws.amazon.com/cognito/latest/developerguide/user-pool-lambda-pre-sign-up.html
     *
     * @var bool
     */
    private $userConfirmed;

    /**
     * In user pools that automatically verify and confirm new users, Amazon Cognito sends users a message with a code or
     * link that confirms ownership of the phone number or email address that they entered. The `CodeDeliveryDetails` object
     * is information about the delivery destination for that link or code.
     *
     * @var CodeDeliveryDetailsType|null
     */
    private $codeDeliveryDetails;

    /**
     * The unique identifier of the new user, for example `a1b2c3d4-5678-90ab-cdef-EXAMPLE11111`.
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
            'DeliveryMedium' => isset($json['DeliveryMedium']) ? (!DeliveryMediumType::exists((string) $json['DeliveryMedium']) ? DeliveryMediumType::UNKNOWN_TO_SDK : (string) $json['DeliveryMedium']) : null,
            'AttributeName' => isset($json['AttributeName']) ? (string) $json['AttributeName'] : null,
        ]);
    }
}
