<?php

namespace AsyncAws\SsoOidc\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;

final class StartDeviceAuthorizationRequest extends Input
{
    /**
     * The unique identifier string for the client that is registered with IAM Identity Center. This value should come from
     * the persisted result of the RegisterClient API operation.
     *
     * @required
     *
     * @var string|null
     */
    private $clientId;

    /**
     * A secret string that is generated for the client. This value should come from the persisted result of the
     * RegisterClient API operation.
     *
     * @required
     *
     * @var string|null
     */
    private $clientSecret;

    /**
     * The URL for the Amazon Web Services access portal. For more information, see Using the Amazon Web Services access
     * portal [^1] in the *IAM Identity Center User Guide*.
     *
     * [^1]: https://docs.aws.amazon.com/singlesignon/latest/userguide/using-the-portal.html
     *
     * @required
     *
     * @var string|null
     */
    private $startUrl;

    /**
     * @param array{
     *   clientId?: string,
     *   clientSecret?: string,
     *   startUrl?: string,
     *   '@region'?: string|null,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->clientId = $input['clientId'] ?? null;
        $this->clientSecret = $input['clientSecret'] ?? null;
        $this->startUrl = $input['startUrl'] ?? null;
        parent::__construct($input);
    }

    /**
     * @param array{
     *   clientId?: string,
     *   clientSecret?: string,
     *   startUrl?: string,
     *   '@region'?: string|null,
     * }|StartDeviceAuthorizationRequest $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getClientId(): ?string
    {
        return $this->clientId;
    }

    public function getClientSecret(): ?string
    {
        return $this->clientSecret;
    }

    public function getStartUrl(): ?string
    {
        return $this->startUrl;
    }

    /**
     * @internal
     */
    public function request(): Request
    {
        // Prepare headers
        $headers = [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ];

        // Prepare query
        $query = [];

        // Prepare URI
        $uriString = '/device_authorization';

        // Prepare Body
        $bodyPayload = $this->requestBody();
        $body = empty($bodyPayload) ? '{}' : json_encode($bodyPayload, 4194304);

        // Return the Request
        return new Request('POST', $uriString, $query, $headers, StreamFactory::create($body));
    }

    public function setClientId(?string $value): self
    {
        $this->clientId = $value;

        return $this;
    }

    public function setClientSecret(?string $value): self
    {
        $this->clientSecret = $value;

        return $this;
    }

    public function setStartUrl(?string $value): self
    {
        $this->startUrl = $value;

        return $this;
    }

    private function requestBody(): array
    {
        $payload = [];
        if (null === $v = $this->clientId) {
            throw new InvalidArgument(\sprintf('Missing parameter "clientId" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['clientId'] = $v;
        if (null === $v = $this->clientSecret) {
            throw new InvalidArgument(\sprintf('Missing parameter "clientSecret" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['clientSecret'] = $v;
        if (null === $v = $this->startUrl) {
            throw new InvalidArgument(\sprintf('Missing parameter "startUrl" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['startUrl'] = $v;

        return $payload;
    }
}
