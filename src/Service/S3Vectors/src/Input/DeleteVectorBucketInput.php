<?php

namespace AsyncAws\S3Vectors\Input;

use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;

final class DeleteVectorBucketInput extends Input
{
    /**
     * The name of the vector bucket to delete.
     *
     * @var string|null
     */
    private $vectorBucketName;

    /**
     * The ARN of the vector bucket to delete.
     *
     * @var string|null
     */
    private $vectorBucketArn;

    /**
     * @param array{
     *   vectorBucketName?: string|null,
     *   vectorBucketArn?: string|null,
     *   '@region'?: string|null,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->vectorBucketName = $input['vectorBucketName'] ?? null;
        $this->vectorBucketArn = $input['vectorBucketArn'] ?? null;
        parent::__construct($input);
    }

    /**
     * @param array{
     *   vectorBucketName?: string|null,
     *   vectorBucketArn?: string|null,
     *   '@region'?: string|null,
     * }|DeleteVectorBucketInput $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getVectorBucketArn(): ?string
    {
        return $this->vectorBucketArn;
    }

    public function getVectorBucketName(): ?string
    {
        return $this->vectorBucketName;
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
        $uriString = '/DeleteVectorBucket';

        // Prepare Body
        $bodyPayload = $this->requestBody();
        $body = empty($bodyPayload) ? '{}' : json_encode($bodyPayload, 4194304);

        // Return the Request
        return new Request('POST', $uriString, $query, $headers, StreamFactory::create($body));
    }

    public function setVectorBucketArn(?string $value): self
    {
        $this->vectorBucketArn = $value;

        return $this;
    }

    public function setVectorBucketName(?string $value): self
    {
        $this->vectorBucketName = $value;

        return $this;
    }

    private function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->vectorBucketName) {
            $payload['vectorBucketName'] = $v;
        }
        if (null !== $v = $this->vectorBucketArn) {
            $payload['vectorBucketArn'] = $v;
        }

        return $payload;
    }
}
