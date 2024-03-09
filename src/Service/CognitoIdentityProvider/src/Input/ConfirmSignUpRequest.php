<?php

namespace AsyncAws\CognitoIdentityProvider\Input;

use AsyncAws\CognitoIdentityProvider\ValueObject\AnalyticsMetadataType;
use AsyncAws\CognitoIdentityProvider\ValueObject\UserContextDataType;
use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;

/**
 * Represents the request to confirm registration of a user.
 */
final class ConfirmSignUpRequest extends Input
{
    /**
     * The ID of the app client associated with the user pool.
     *
     * @required
     *
     * @var string|null
     */
    private $clientId;

    /**
     * A keyed-hash message authentication code (HMAC) calculated using the secret key of a user pool client and username
     * plus the client ID in the message.
     *
     * @var string|null
     */
    private $secretHash;

    /**
     * The username of the user that you want to query or modify. The value of this parameter is typically your user's
     * username, but it can be any of their alias attributes. If `username` isn't an alias attribute in your user pool, this
     * value must be the `sub` of a local user or the username of a user from a third-party IdP.
     *
     * @required
     *
     * @var string|null
     */
    private $username;

    /**
     * The confirmation code sent by a user's request to confirm registration.
     *
     * @required
     *
     * @var string|null
     */
    private $confirmationCode;

    /**
     * Boolean to be specified to force user confirmation irrespective of existing alias. By default set to `False`. If this
     * parameter is set to `True` and the phone number/email used for sign up confirmation already exists as an alias with a
     * different user, the API call will migrate the alias from the previous user to the newly created user being confirmed.
     * If set to `False`, the API will throw an **AliasExistsException** error.
     *
     * @var bool|null
     */
    private $forceAliasCreation;

    /**
     * The Amazon Pinpoint analytics metadata for collecting metrics for `ConfirmSignUp` calls.
     *
     * @var AnalyticsMetadataType|null
     */
    private $analyticsMetadata;

    /**
     * Contextual data about your user session, such as the device fingerprint, IP address, or location. Amazon Cognito
     * advanced security evaluates the risk of an authentication event based on the context that your app generates and
     * passes to Amazon Cognito when it makes API requests.
     *
     * @var UserContextDataType|null
     */
    private $userContextData;

    /**
     * A map of custom key-value pairs that you can provide as input for any custom workflows that this action triggers.
     *
     * You create custom workflows by assigning Lambda functions to user pool triggers. When you use the ConfirmSignUp API
     * action, Amazon Cognito invokes the function that is assigned to the *post confirmation* trigger. When Amazon Cognito
     * invokes this function, it passes a JSON payload, which the function receives as input. This payload contains a
     * `clientMetadata` attribute, which provides the data that you assigned to the ClientMetadata parameter in your
     * ConfirmSignUp request. In your function code in Lambda, you can process the `clientMetadata` value to enhance your
     * workflow for your specific needs.
     *
     * For more information, see Customizing user pool Workflows with Lambda Triggers [^1] in the *Amazon Cognito Developer
     * Guide*.
     *
     * > When you use the ClientMetadata parameter, remember that Amazon Cognito won't do the following:
     * >
     * > - Store the ClientMetadata value. This data is available only to Lambda triggers that are assigned to a user pool
     * >   to support custom workflows. If your user pool configuration doesn't include triggers, the ClientMetadata
     * >   parameter serves no purpose.
     * > - Validate the ClientMetadata value.
     * > - Encrypt the ClientMetadata value. Don't use Amazon Cognito to provide sensitive information.
     * >
     *
     * [^1]: https://docs.aws.amazon.com/cognito/latest/developerguide/cognito-user-identity-pools-working-with-aws-lambda-triggers.html
     *
     * @var array<string, string>|null
     */
    private $clientMetadata;

    /**
     * @param array{
     *   ClientId?: string,
     *   SecretHash?: null|string,
     *   Username?: string,
     *   ConfirmationCode?: string,
     *   ForceAliasCreation?: null|bool,
     *   AnalyticsMetadata?: null|AnalyticsMetadataType|array,
     *   UserContextData?: null|UserContextDataType|array,
     *   ClientMetadata?: null|array<string, string>,
     *   '@region'?: string|null,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->clientId = $input['ClientId'] ?? null;
        $this->secretHash = $input['SecretHash'] ?? null;
        $this->username = $input['Username'] ?? null;
        $this->confirmationCode = $input['ConfirmationCode'] ?? null;
        $this->forceAliasCreation = $input['ForceAliasCreation'] ?? null;
        $this->analyticsMetadata = isset($input['AnalyticsMetadata']) ? AnalyticsMetadataType::create($input['AnalyticsMetadata']) : null;
        $this->userContextData = isset($input['UserContextData']) ? UserContextDataType::create($input['UserContextData']) : null;
        $this->clientMetadata = $input['ClientMetadata'] ?? null;
        parent::__construct($input);
    }

    /**
     * @param array{
     *   ClientId?: string,
     *   SecretHash?: null|string,
     *   Username?: string,
     *   ConfirmationCode?: string,
     *   ForceAliasCreation?: null|bool,
     *   AnalyticsMetadata?: null|AnalyticsMetadataType|array,
     *   UserContextData?: null|UserContextDataType|array,
     *   ClientMetadata?: null|array<string, string>,
     *   '@region'?: string|null,
     * }|ConfirmSignUpRequest $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getAnalyticsMetadata(): ?AnalyticsMetadataType
    {
        return $this->analyticsMetadata;
    }

    public function getClientId(): ?string
    {
        return $this->clientId;
    }

    /**
     * @return array<string, string>
     */
    public function getClientMetadata(): array
    {
        return $this->clientMetadata ?? [];
    }

    public function getConfirmationCode(): ?string
    {
        return $this->confirmationCode;
    }

    public function getForceAliasCreation(): ?bool
    {
        return $this->forceAliasCreation;
    }

    public function getSecretHash(): ?string
    {
        return $this->secretHash;
    }

    public function getUserContextData(): ?UserContextDataType
    {
        return $this->userContextData;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    /**
     * @internal
     */
    public function request(): Request
    {
        // Prepare headers
        $headers = [
            'Content-Type' => 'application/x-amz-json-1.1',
            'X-Amz-Target' => 'AWSCognitoIdentityProviderService.ConfirmSignUp',
        ];

        // Prepare query
        $query = [];

        // Prepare URI
        $uriString = '/';

        // Prepare Body
        $bodyPayload = $this->requestBody();
        $body = empty($bodyPayload) ? '{}' : json_encode($bodyPayload, 4194304);

        // Return the Request
        return new Request('POST', $uriString, $query, $headers, StreamFactory::create($body));
    }

    public function setAnalyticsMetadata(?AnalyticsMetadataType $value): self
    {
        $this->analyticsMetadata = $value;

        return $this;
    }

    public function setClientId(?string $value): self
    {
        $this->clientId = $value;

        return $this;
    }

    /**
     * @param array<string, string> $value
     */
    public function setClientMetadata(array $value): self
    {
        $this->clientMetadata = $value;

        return $this;
    }

    public function setConfirmationCode(?string $value): self
    {
        $this->confirmationCode = $value;

        return $this;
    }

    public function setForceAliasCreation(?bool $value): self
    {
        $this->forceAliasCreation = $value;

        return $this;
    }

    public function setSecretHash(?string $value): self
    {
        $this->secretHash = $value;

        return $this;
    }

    public function setUserContextData(?UserContextDataType $value): self
    {
        $this->userContextData = $value;

        return $this;
    }

    public function setUsername(?string $value): self
    {
        $this->username = $value;

        return $this;
    }

    private function requestBody(): array
    {
        $payload = [];
        if (null === $v = $this->clientId) {
            throw new InvalidArgument(sprintf('Missing parameter "ClientId" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['ClientId'] = $v;
        if (null !== $v = $this->secretHash) {
            $payload['SecretHash'] = $v;
        }
        if (null === $v = $this->username) {
            throw new InvalidArgument(sprintf('Missing parameter "Username" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['Username'] = $v;
        if (null === $v = $this->confirmationCode) {
            throw new InvalidArgument(sprintf('Missing parameter "ConfirmationCode" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['ConfirmationCode'] = $v;
        if (null !== $v = $this->forceAliasCreation) {
            $payload['ForceAliasCreation'] = (bool) $v;
        }
        if (null !== $v = $this->analyticsMetadata) {
            $payload['AnalyticsMetadata'] = $v->requestBody();
        }
        if (null !== $v = $this->userContextData) {
            $payload['UserContextData'] = $v->requestBody();
        }
        if (null !== $v = $this->clientMetadata) {
            if (empty($v)) {
                $payload['ClientMetadata'] = new \stdClass();
            } else {
                $payload['ClientMetadata'] = [];
                foreach ($v as $name => $mv) {
                    $payload['ClientMetadata'][$name] = $mv;
                }
            }
        }

        return $payload;
    }
}
