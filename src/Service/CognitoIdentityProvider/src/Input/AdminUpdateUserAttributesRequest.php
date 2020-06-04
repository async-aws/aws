<?php

namespace AsyncAws\CognitoIdentityProvider\Input;

use AsyncAws\CognitoIdentityProvider\ValueObject\AttributeType;
use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;

final class AdminUpdateUserAttributesRequest extends Input
{
    /**
     * The user pool ID for the user pool where you want to update user attributes.
     *
     * @required
     *
     * @var string|null
     */
    private $UserPoolId;

    /**
     * The user name of the user for whom you want to update user attributes.
     *
     * @required
     *
     * @var string|null
     */
    private $Username;

    /**
     * An array of name-value pairs representing user attributes.
     *
     * @required
     *
     * @var AttributeType[]|null
     */
    private $UserAttributes;

    /**
     * A map of custom key-value pairs that you can provide as input for any custom workflows that this action triggers.
     *
     * @var array<string, string>|null
     */
    private $ClientMetadata;

    /**
     * @param array{
     *   UserPoolId?: string,
     *   Username?: string,
     *   UserAttributes?: \AsyncAws\CognitoIdentityProvider\ValueObject\AttributeType[],
     *   ClientMetadata?: array<string, string>,
     *   @region?: string,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->UserPoolId = $input['UserPoolId'] ?? null;
        $this->Username = $input['Username'] ?? null;
        $this->UserAttributes = isset($input['UserAttributes']) ? array_map([AttributeType::class, 'create'], $input['UserAttributes']) : null;
        $this->ClientMetadata = $input['ClientMetadata'] ?? null;
        parent::__construct($input);
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return array<string, string>
     */
    public function getClientMetadata(): array
    {
        return $this->ClientMetadata ?? [];
    }

    /**
     * @return AttributeType[]
     */
    public function getUserAttributes(): array
    {
        return $this->UserAttributes ?? [];
    }

    public function getUserPoolId(): ?string
    {
        return $this->UserPoolId;
    }

    public function getUsername(): ?string
    {
        return $this->Username;
    }

    /**
     * @internal
     */
    public function request(): Request
    {
        // Prepare headers
        $headers = [
            'Content-Type' => 'application/x-amz-json-1.1',
            'X-Amz-Target' => 'AWSCognitoIdentityProviderService.AdminUpdateUserAttributes',
        ];

        // Prepare query
        $query = [];

        // Prepare URI
        $uriString = '/';

        // Prepare Body
        $bodyPayload = $this->requestBody();
        $body = empty($bodyPayload) ? '{}' : json_encode($bodyPayload);

        // Return the Request
        return new Request('POST', $uriString, $query, $headers, StreamFactory::create($body));
    }

    /**
     * @param array<string, string> $value
     */
    public function setClientMetadata(array $value): self
    {
        $this->ClientMetadata = $value;

        return $this;
    }

    /**
     * @param AttributeType[] $value
     */
    public function setUserAttributes(array $value): self
    {
        $this->UserAttributes = $value;

        return $this;
    }

    public function setUserPoolId(?string $value): self
    {
        $this->UserPoolId = $value;

        return $this;
    }

    public function setUsername(?string $value): self
    {
        $this->Username = $value;

        return $this;
    }

    private function requestBody(): array
    {
        $payload = [];
        if (null === $v = $this->UserPoolId) {
            throw new InvalidArgument(sprintf('Missing parameter "UserPoolId" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['UserPoolId'] = $v;
        if (null === $v = $this->Username) {
            throw new InvalidArgument(sprintf('Missing parameter "Username" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['Username'] = $v;
        if (null === $v = $this->UserAttributes) {
            throw new InvalidArgument(sprintf('Missing parameter "UserAttributes" for "%s". The value cannot be null.', __CLASS__));
        }

        $index = -1;
        $payload['UserAttributes'] = [];
        foreach ($v as $listValue) {
            ++$index;
            $payload['UserAttributes'][$index] = $listValue->requestBody();
        }

        if (null !== $v = $this->ClientMetadata) {
            foreach ($v as $name => $v) {
                $payload['ClientMetadata'][$name] = $v;
            }
        }

        return $payload;
    }
}
