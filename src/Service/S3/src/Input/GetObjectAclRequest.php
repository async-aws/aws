<?php

namespace AsyncAws\S3\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\S3\Enum\RequestPayer;

class GetObjectAclRequest
{
    /**
     * The bucket name that contains the object for which to get the ACL information.
     *
     * @required
     *
     * @var string|null
     */
    private $Bucket;

    /**
     * The key of the object for which to get the ACL information.
     *
     * @required
     *
     * @var string|null
     */
    private $Key;

    /**
     * VersionId used to reference a specific version of the object.
     *
     * @var string|null
     */
    private $VersionId;

    /**
     * @var RequestPayer::*|null
     */
    private $RequestPayer;

    /**
     * @see http://docs.amazonwebservices.com/AmazonS3/latest/API/RESTObjectGETacl.html
     *
     * @param array{
     *   Bucket?: string,
     *   Key?: string,
     *   VersionId?: string,
     *   RequestPayer?: \AsyncAws\S3\Enum\RequestPayer::*,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->Bucket = $input['Bucket'] ?? null;
        $this->Key = $input['Key'] ?? null;
        $this->VersionId = $input['VersionId'] ?? null;
        $this->RequestPayer = $input['RequestPayer'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getBucket(): ?string
    {
        return $this->Bucket;
    }

    public function getKey(): ?string
    {
        return $this->Key;
    }

    /**
     * @return RequestPayer::*|null
     */
    public function getRequestPayer(): ?string
    {
        return $this->RequestPayer;
    }

    public function getVersionId(): ?string
    {
        return $this->VersionId;
    }

    public function requestHeaders(): array
    {
        $headers = ['content-type' => 'application/xml'];
        if (null !== $this->RequestPayer) {
            $headers['x-amz-request-payer'] = $this->RequestPayer;
        }

        return $headers;
    }

    public function requestQuery(): array
    {
        $query = [];
        if (null !== $this->VersionId) {
            $query['versionId'] = $this->VersionId;
        }

        return $query;
    }

    public function requestUri(): string
    {
        $uri = [];
        $uri['Bucket'] = $this->Bucket ?? '';
        $uri['Key'] = $this->Key ?? '';

        return "/{$uri['Bucket']}/{$uri['Key']}?acl";
    }

    public function setBucket(?string $value): self
    {
        $this->Bucket = $value;

        return $this;
    }

    public function setKey(?string $value): self
    {
        $this->Key = $value;

        return $this;
    }

    /**
     * @param RequestPayer::*|null $value
     */
    public function setRequestPayer(?string $value): self
    {
        $this->RequestPayer = $value;

        return $this;
    }

    public function setVersionId(?string $value): self
    {
        $this->VersionId = $value;

        return $this;
    }

    public function validate(): void
    {
        if (null === $this->Bucket) {
            throw new InvalidArgument(sprintf('Missing parameter "Bucket" when validating the "%s". The value cannot be null.', __CLASS__));
        }

        if (null === $this->Key) {
            throw new InvalidArgument(sprintf('Missing parameter "Key" when validating the "%s". The value cannot be null.', __CLASS__));
        }

        if (null !== $this->RequestPayer) {
            if (!RequestPayer::exists($this->RequestPayer)) {
                throw new InvalidArgument(sprintf('Invalid parameter "RequestPayer" when validating the "%s". The value "%s" is not a valid "RequestPayer".', __CLASS__, $this->RequestPayer));
            }
        }
    }
}
