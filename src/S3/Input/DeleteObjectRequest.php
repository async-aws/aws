<?php

namespace AsyncAws\S3\Input;

use AsyncAws\Core\Exception\InvalidArgument;

class DeleteObjectRequest
{
    /**
     * The bucket name of the bucket containing the object.
     *
     * @required
     *
     * @var string|null
     */
    private $Bucket;

    /**
     * Key name of the object to delete.
     *
     * @required
     *
     * @var string|null
     */
    private $Key;

    /**
     * The concatenation of the authentication device's serial number, a space, and the value that is displayed on your
     * authentication device. Required to permanently delete a versioned object if versioning is configured with MFA delete
     * enabled.
     *
     * @var string|null
     */
    private $MFA;

    /**
     * VersionId used to reference a specific version of the object.
     *
     * @var string|null
     */
    private $VersionId;

    /**
     * @var string|null
     */
    private $RequestPayer;

    /**
     * Indicates whether S3 Object Lock should bypass Governance-mode restrictions to process this operation.
     *
     * @var bool|null
     */
    private $BypassGovernanceRetention;

    /**
     * @see http://docs.amazonwebservices.com/AmazonS3/latest/API/RESTObjectDELETE.html
     *
     * @param array{
     *   Bucket: string,
     *   Key: string,
     *   MFA?: string,
     *   VersionId?: string,
     *   RequestPayer?: string,
     *   BypassGovernanceRetention?: bool,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->Bucket = $input['Bucket'] ?? null;
        $this->Key = $input['Key'] ?? null;
        $this->MFA = $input['MFA'] ?? null;
        $this->VersionId = $input['VersionId'] ?? null;
        $this->RequestPayer = $input['RequestPayer'] ?? null;
        $this->BypassGovernanceRetention = $input['BypassGovernanceRetention'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getBucket(): ?string
    {
        return $this->Bucket;
    }

    public function getBypassGovernanceRetention(): ?bool
    {
        return $this->BypassGovernanceRetention;
    }

    public function getKey(): ?string
    {
        return $this->Key;
    }

    public function getMFA(): ?string
    {
        return $this->MFA;
    }

    public function getRequestPayer(): ?string
    {
        return $this->RequestPayer;
    }

    public function getVersionId(): ?string
    {
        return $this->VersionId;
    }

    public function requestBody(): array
    {
        $payload = ['Action' => 'DeleteObject', 'Version' => '2006-03-01'];

        return $payload;
    }

    public function requestHeaders(): array
    {
        $headers = [];
        if (null !== $this->MFA) {
            $headers['x-amz-mfa'] = $this->MFA;
        }
        if (null !== $this->RequestPayer) {
            $headers['x-amz-request-payer'] = $this->RequestPayer;
        }
        if (null !== $this->BypassGovernanceRetention) {
            $headers['x-amz-bypass-governance-retention'] = $this->BypassGovernanceRetention;
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

        return "/{$uri['Bucket']}/{$uri['Key']}";
    }

    public function setBucket(?string $value): self
    {
        $this->Bucket = $value;

        return $this;
    }

    public function setBypassGovernanceRetention(?bool $value): self
    {
        $this->BypassGovernanceRetention = $value;

        return $this;
    }

    public function setKey(?string $value): self
    {
        $this->Key = $value;

        return $this;
    }

    public function setMFA(?string $value): self
    {
        $this->MFA = $value;

        return $this;
    }

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
        foreach (['Bucket', 'Key'] as $name) {
            if (null === $this->$name) {
                throw new InvalidArgument(sprintf('Missing parameter "%s" when validating the "%s". The value cannot be null.', $name, __CLASS__));
            }
        }
    }
}
