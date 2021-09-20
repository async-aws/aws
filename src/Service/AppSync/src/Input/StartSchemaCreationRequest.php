<?php

namespace AsyncAws\AppSync\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;

final class StartSchemaCreationRequest extends Input
{
    /**
     * The API ID.
     *
     * @required
     *
     * @var string|null
     */
    private $apiId;

    /**
     * The schema definition, in GraphQL schema language format.
     *
     * @required
     *
     * @var string|null
     */
    private $definition;

    /**
     * @param array{
     *   apiId?: string,
     *   definition?: string,
     *   @region?: string,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->apiId = $input['apiId'] ?? null;
        $this->definition = $input['definition'] ?? null;
        parent::__construct($input);
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getApiId(): ?string
    {
        return $this->apiId;
    }

    public function getDefinition(): ?string
    {
        return $this->definition;
    }

    /**
     * @internal
     */
    public function request(): Request
    {
        // Prepare headers
        $headers = ['content-type' => 'application/json'];

        // Prepare query
        $query = [];

        // Prepare URI
        $uri = [];
        if (null === $v = $this->apiId) {
            throw new InvalidArgument(sprintf('Missing parameter "apiId" for "%s". The value cannot be null.', __CLASS__));
        }
        $uri['apiId'] = $v;
        $uriString = '/v1/apis/' . rawurlencode($uri['apiId']) . '/schemacreation';

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

    public function setDefinition(?string $value): self
    {
        $this->definition = $value;

        return $this;
    }

    private function requestBody(): array
    {
        $payload = [];

        if (null === $v = $this->definition) {
            throw new InvalidArgument(sprintf('Missing parameter "definition" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['definition'] = base64_encode($v);

        return $payload;
    }
}
