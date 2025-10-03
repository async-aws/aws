<?php

namespace AsyncAws\RdsDataService\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;

/**
 * The request parameters represent the input of a request to start a SQL transaction.
 */
final class BeginTransactionRequest extends Input
{
    /**
     * The Amazon Resource Name (ARN) of the Aurora Serverless DB cluster.
     *
     * @required
     *
     * @var string|null
     */
    private $resourceArn;

    /**
     * The name or ARN of the secret that enables access to the DB cluster.
     *
     * @required
     *
     * @var string|null
     */
    private $secretArn;

    /**
     * The name of the database.
     *
     * @var string|null
     */
    private $database;

    /**
     * The name of the database schema.
     *
     * @var string|null
     */
    private $schema;

    /**
     * @param array{
     *   resourceArn?: string,
     *   secretArn?: string,
     *   database?: string|null,
     *   schema?: string|null,
     *   '@region'?: string|null,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->resourceArn = $input['resourceArn'] ?? null;
        $this->secretArn = $input['secretArn'] ?? null;
        $this->database = $input['database'] ?? null;
        $this->schema = $input['schema'] ?? null;
        parent::__construct($input);
    }

    /**
     * @param array{
     *   resourceArn?: string,
     *   secretArn?: string,
     *   database?: string|null,
     *   schema?: string|null,
     *   '@region'?: string|null,
     * }|BeginTransactionRequest $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getDatabase(): ?string
    {
        return $this->database;
    }

    public function getResourceArn(): ?string
    {
        return $this->resourceArn;
    }

    public function getSchema(): ?string
    {
        return $this->schema;
    }

    public function getSecretArn(): ?string
    {
        return $this->secretArn;
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
        $uriString = '/BeginTransaction';

        // Prepare Body
        $bodyPayload = $this->requestBody();
        $body = empty($bodyPayload) ? '{}' : json_encode($bodyPayload, 4194304);

        // Return the Request
        return new Request('POST', $uriString, $query, $headers, StreamFactory::create($body));
    }

    public function setDatabase(?string $value): self
    {
        $this->database = $value;

        return $this;
    }

    public function setResourceArn(?string $value): self
    {
        $this->resourceArn = $value;

        return $this;
    }

    public function setSchema(?string $value): self
    {
        $this->schema = $value;

        return $this;
    }

    public function setSecretArn(?string $value): self
    {
        $this->secretArn = $value;

        return $this;
    }

    private function requestBody(): array
    {
        $payload = [];
        if (null === $v = $this->resourceArn) {
            throw new InvalidArgument(\sprintf('Missing parameter "resourceArn" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['resourceArn'] = $v;
        if (null === $v = $this->secretArn) {
            throw new InvalidArgument(\sprintf('Missing parameter "secretArn" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['secretArn'] = $v;
        if (null !== $v = $this->database) {
            $payload['database'] = $v;
        }
        if (null !== $v = $this->schema) {
            $payload['schema'] = $v;
        }

        return $payload;
    }
}
