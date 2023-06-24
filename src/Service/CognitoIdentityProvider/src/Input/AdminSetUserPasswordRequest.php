<?php

namespace AsyncAws\CognitoIdentityProvider\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;

final class AdminSetUserPasswordRequest extends Input
{
    /**
     * The user pool ID for the user pool where you want to set the user's password.
     *
     * @required
     *
     * @var string|null
     */
    private $userPoolId;

    /**
     * The user name of the user whose password you want to set.
     *
     * @required
     *
     * @var string|null
     */
    private $username;

    /**
     * The password for the user.
     *
     * @required
     *
     * @var string|null
     */
    private $password;

    /**
     * `True` if the password is permanent, `False` if it is temporary.
     *
     * @var bool|null
     */
    private $permanent;

    /**
     * @param array{
     *   UserPoolId?: string,
     *   Username?: string,
     *   Password?: string,
     *   Permanent?: bool,
     *   '@region'?: string|null,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->userPoolId = $input['UserPoolId'] ?? null;
        $this->username = $input['Username'] ?? null;
        $this->password = $input['Password'] ?? null;
        $this->permanent = $input['Permanent'] ?? null;
        parent::__construct($input);
    }

    /**
     * @param array{
     *   UserPoolId?: string,
     *   Username?: string,
     *   Password?: string,
     *   Permanent?: bool,
     *   '@region'?: string|null,
     * }|AdminSetUserPasswordRequest $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function getPermanent(): ?bool
    {
        return $this->permanent;
    }

    public function getUserPoolId(): ?string
    {
        return $this->userPoolId;
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
            'X-Amz-Target' => 'AWSCognitoIdentityProviderService.AdminSetUserPassword',
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

    public function setPassword(?string $value): self
    {
        $this->password = $value;

        return $this;
    }

    public function setPermanent(?bool $value): self
    {
        $this->permanent = $value;

        return $this;
    }

    public function setUserPoolId(?string $value): self
    {
        $this->userPoolId = $value;

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
        if (null === $v = $this->userPoolId) {
            throw new InvalidArgument(sprintf('Missing parameter "UserPoolId" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['UserPoolId'] = $v;
        if (null === $v = $this->username) {
            throw new InvalidArgument(sprintf('Missing parameter "Username" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['Username'] = $v;
        if (null === $v = $this->password) {
            throw new InvalidArgument(sprintf('Missing parameter "Password" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['Password'] = $v;
        if (null !== $v = $this->permanent) {
            $payload['Permanent'] = (bool) $v;
        }

        return $payload;
    }
}
