<?php

namespace AsyncAws\RdsDataService\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;

final class RollbackTransactionRequest extends Input
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
     * The identifier of the transaction to roll back.
     *
     * @required
     *
     * @var string|null
     */
    private $transactionId;

    /**
     * @param array{
     *   resourceArn?: string,
     *   secretArn?: string,
     *   transactionId?: string,
     *   @region?: string,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->resourceArn = $input['resourceArn'] ?? null;
        $this->secretArn = $input['secretArn'] ?? null;
        $this->transactionId = $input['transactionId'] ?? null;
        parent::__construct($input);
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getResourceArn(): ?string
    {
        return $this->resourceArn;
    }

    public function getSecretArn(): ?string
    {
        return $this->secretArn;
    }

    public function getTransactionId(): ?string
    {
        return $this->transactionId;
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
        $uriString = '/RollbackTransaction';

        // Prepare Body
        $bodyPayload = $this->requestBody();
        $body = empty($bodyPayload) ? '{}' : json_encode($bodyPayload);

        // Return the Request
        return new Request('POST', $uriString, $query, $headers, StreamFactory::create($body));
    }

    public function setResourceArn(?string $value): self
    {
        $this->resourceArn = $value;

        return $this;
    }

    public function setSecretArn(?string $value): self
    {
        $this->secretArn = $value;

        return $this;
    }

    public function setTransactionId(?string $value): self
    {
        $this->transactionId = $value;

        return $this;
    }

    private function requestBody(): array
    {
        $payload = [];
        if (null === $v = $this->resourceArn) {
            throw new InvalidArgument(sprintf('Missing parameter "resourceArn" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['resourceArn'] = $v;
        if (null === $v = $this->secretArn) {
            throw new InvalidArgument(sprintf('Missing parameter "secretArn" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['secretArn'] = $v;
        if (null === $v = $this->transactionId) {
            throw new InvalidArgument(sprintf('Missing parameter "transactionId" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['transactionId'] = $v;

        return $payload;
    }
}
