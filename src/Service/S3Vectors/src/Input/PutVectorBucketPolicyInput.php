<?php

namespace AsyncAws\S3Vectors\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;

final class PutVectorBucketPolicyInput extends Input
{
    /**
     * The name of the vector bucket.
     *
     * @var string|null
     */
    private $vectorBucketName;

    /**
     * The Amazon Resource Name (ARN) of the vector bucket.
     *
     * @var string|null
     */
    private $vectorBucketArn;

    /**
     * The `JSON` that defines the policy. For more information about bucket policies for S3 Vectors, see Managing vector
     * bucket policies [^1] in the *Amazon S3 User Guide*.
     *
     * [^1]: https://docs.aws.amazon.com/AmazonS3/latest/userguide/s3-vectors-bucket-policy.html
     *
     * @required
     *
     * @var string|null
     */
    private $policy;

    /**
     * @param array{
     *   vectorBucketName?: string|null,
     *   vectorBucketArn?: string|null,
     *   policy?: string,
     *   '@region'?: string|null,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->vectorBucketName = $input['vectorBucketName'] ?? null;
        $this->vectorBucketArn = $input['vectorBucketArn'] ?? null;
        $this->policy = $input['policy'] ?? null;
        parent::__construct($input);
    }

    /**
     * @param array{
     *   vectorBucketName?: string|null,
     *   vectorBucketArn?: string|null,
     *   policy?: string,
     *   '@region'?: string|null,
     * }|PutVectorBucketPolicyInput $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getPolicy(): ?string
    {
        return $this->policy;
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
        $uriString = '/PutVectorBucketPolicy';

        // Prepare Body
        $bodyPayload = $this->requestBody();
        $body = empty($bodyPayload) ? '{}' : json_encode($bodyPayload, 4194304);

        // Return the Request
        return new Request('POST', $uriString, $query, $headers, StreamFactory::create($body));
    }

    public function setPolicy(?string $value): self
    {
        $this->policy = $value;

        return $this;
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
        if (null === $v = $this->policy) {
            throw new InvalidArgument(\sprintf('Missing parameter "policy" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['policy'] = $v;

        return $payload;
    }
}
