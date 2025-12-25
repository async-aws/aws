<?php

namespace AsyncAws\S3Vectors\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;
use AsyncAws\S3Vectors\ValueObject\EncryptionConfiguration;

final class CreateVectorBucketInput extends Input
{
    /**
     * The name of the vector bucket to create.
     *
     * @required
     *
     * @var string|null
     */
    private $vectorBucketName;

    /**
     * The encryption configuration for the vector bucket. By default, if you don't specify, all new vectors in Amazon S3
     * vector buckets use server-side encryption with Amazon S3 managed keys (SSE-S3), specifically `AES256`.
     *
     * @var EncryptionConfiguration|null
     */
    private $encryptionConfiguration;

    /**
     * An array of user-defined tags that you would like to apply to the vector bucket that you are creating. A tag is a
     * key-value pair that you apply to your resources. Tags can help you organize and control access to resources. For more
     * information, see Tagging for cost allocation or attribute-based access control (ABAC) [^1].
     *
     * > You must have the `s3vectors:TagResource` permission in addition to `s3vectors:CreateVectorBucket` permission to
     * > create a vector bucket with tags.
     *
     * [^1]: https://docs.aws.amazon.com/AmazonS3/latest/userguide/tagging.html
     *
     * @var array<string, string>|null
     */
    private $tags;

    /**
     * @param array{
     *   vectorBucketName?: string,
     *   encryptionConfiguration?: EncryptionConfiguration|array|null,
     *   tags?: array<string, string>|null,
     *   '@region'?: string|null,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->vectorBucketName = $input['vectorBucketName'] ?? null;
        $this->encryptionConfiguration = isset($input['encryptionConfiguration']) ? EncryptionConfiguration::create($input['encryptionConfiguration']) : null;
        $this->tags = $input['tags'] ?? null;
        parent::__construct($input);
    }

    /**
     * @param array{
     *   vectorBucketName?: string,
     *   encryptionConfiguration?: EncryptionConfiguration|array|null,
     *   tags?: array<string, string>|null,
     *   '@region'?: string|null,
     * }|CreateVectorBucketInput $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getEncryptionConfiguration(): ?EncryptionConfiguration
    {
        return $this->encryptionConfiguration;
    }

    /**
     * @return array<string, string>
     */
    public function getTags(): array
    {
        return $this->tags ?? [];
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
        $uriString = '/CreateVectorBucket';

        // Prepare Body
        $bodyPayload = $this->requestBody();
        $body = empty($bodyPayload) ? '{}' : json_encode($bodyPayload, 4194304);

        // Return the Request
        return new Request('POST', $uriString, $query, $headers, StreamFactory::create($body));
    }

    public function setEncryptionConfiguration(?EncryptionConfiguration $value): self
    {
        $this->encryptionConfiguration = $value;

        return $this;
    }

    /**
     * @param array<string, string> $value
     */
    public function setTags(array $value): self
    {
        $this->tags = $value;

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
        if (null === $v = $this->vectorBucketName) {
            throw new InvalidArgument(\sprintf('Missing parameter "vectorBucketName" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['vectorBucketName'] = $v;
        if (null !== $v = $this->encryptionConfiguration) {
            $payload['encryptionConfiguration'] = $v->requestBody();
        }
        if (null !== $v = $this->tags) {
            if (empty($v)) {
                $payload['tags'] = new \stdClass();
            } else {
                $payload['tags'] = [];
                foreach ($v as $name => $mv) {
                    $payload['tags'][$name] = $mv;
                }
            }
        }

        return $payload;
    }
}
