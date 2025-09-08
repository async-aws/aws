<?php

namespace AsyncAws\AppSync\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;

final class UpdateApiKeyRequest extends Input
{
    /**
     * The ID for the GraphQL API.
     *
     * @required
     *
     * @var string|null
     */
    private $apiId;

    /**
     * The API key ID.
     *
     * @required
     *
     * @var string|null
     */
    private $id;

    /**
     * A description of the purpose of the API key.
     *
     * @var string|null
     */
    private $description;

    /**
     * From the update time, the time after which the API key expires. The date is represented as seconds since the epoch.
     * For more information, see .
     *
     * @var int|null
     */
    private $expires;

    /**
     * @param array{
     *   apiId?: string,
     *   id?: string,
     *   description?: string|null,
     *   expires?: int|null,
     *   '@region'?: string|null,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->apiId = $input['apiId'] ?? null;
        $this->id = $input['id'] ?? null;
        $this->description = $input['description'] ?? null;
        $this->expires = $input['expires'] ?? null;
        parent::__construct($input);
    }

    /**
     * @param array{
     *   apiId?: string,
     *   id?: string,
     *   description?: string|null,
     *   expires?: int|null,
     *   '@region'?: string|null,
     * }|UpdateApiKeyRequest $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getApiId(): ?string
    {
        return $this->apiId;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getExpires(): ?int
    {
        return $this->expires;
    }

    public function getId(): ?string
    {
        return $this->id;
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
        $uri = [];
        if (null === $v = $this->apiId) {
            throw new InvalidArgument(\sprintf('Missing parameter "apiId" for "%s". The value cannot be null.', __CLASS__));
        }
        $uri['apiId'] = $v;
        if (null === $v = $this->id) {
            throw new InvalidArgument(\sprintf('Missing parameter "id" for "%s". The value cannot be null.', __CLASS__));
        }
        $uri['id'] = $v;
        $uriString = '/v1/apis/' . rawurlencode($uri['apiId']) . '/apikeys/' . rawurlencode($uri['id']);

        // Prepare Body
        $bodyPayload = $this->requestBody();
        $body = empty($bodyPayload) ? '{}' : json_encode($bodyPayload, 4194304);

        // Return the Request
        return new Request('POST', $uriString, $query, $headers, StreamFactory::create($body));
    }

    public function setApiId(?string $value): self
    {
        $this->apiId = $value;

        return $this;
    }

    public function setDescription(?string $value): self
    {
        $this->description = $value;

        return $this;
    }

    public function setExpires(?int $value): self
    {
        $this->expires = $value;

        return $this;
    }

    public function setId(?string $value): self
    {
        $this->id = $value;

        return $this;
    }

    private function requestBody(): array
    {
        $payload = [];

        if (null !== $v = $this->description) {
            $payload['description'] = $v;
        }
        if (null !== $v = $this->expires) {
            $payload['expires'] = $v;
        }

        return $payload;
    }
}
