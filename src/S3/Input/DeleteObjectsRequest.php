<?php

namespace AsyncAws\S3\Input;

use AsyncAws\Core\Exception\InvalidArgument;

class DeleteObjectsRequest
{
    /**
     * The bucket name containing the objects to delete.
     *
     * @required
     *
     * @var string|null
     */
    private $Bucket;

    /**
     * Container for the request.
     *
     * @required
     *
     * @var Delete|null
     */
    private $Delete;

    /**
     * The concatenation of the authentication device's serial number, a space, and the value that is displayed on your
     * authentication device. Required to permanently delete a versioned object if versioning is configured with MFA delete
     * enabled.
     *
     * @var string|null
     */
    private $MFA;

    /**
     * @var string|null
     */
    private $RequestPayer;

    /**
     * Specifies whether you want to delete this object even if it has a Governance-type Object Lock in place. You must have
     * sufficient permissions to perform this operation.
     *
     * @var bool|null
     */
    private $BypassGovernanceRetention;

    /**
     * @see http://docs.amazonwebservices.com/AmazonS3/latest/API/multiobjectdeleteapi.html
     *
     * @param array{
     *   Bucket?: string,
     *   Delete?: \AsyncAws\S3\Input\Delete|array,
     *   MFA?: string,
     *   RequestPayer?: string,
     *   BypassGovernanceRetention?: bool,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->Bucket = $input['Bucket'] ?? null;
        $this->Delete = isset($input['Delete']) ? Delete::create($input['Delete']) : null;
        $this->MFA = $input['MFA'] ?? null;
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

    public function getDelete(): ?Delete
    {
        return $this->Delete;
    }

    public function getMFA(): ?string
    {
        return $this->MFA;
    }

    public function getRequestPayer(): ?string
    {
        return $this->RequestPayer;
    }

    public function requestBody(): array
    {
        $payload = ['Action' => 'DeleteObjects', 'Version' => '2006-03-01'];
        if (null !== $this->Delete) {
            $payload['Delete'] = $this->Delete;
        }

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

        return $query;
    }

    public function requestUri(): string
    {
        $uri = [];
        $uri['Bucket'] = $this->Bucket ?? '';

        return "/{$uri['Bucket']}?delete";
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

    public function setDelete(?Delete $value): self
    {
        $this->Delete = $value;

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

    public function validate(): void
    {
        foreach (['Bucket', 'Delete'] as $name) {
            if (null === $this->$name) {
                throw new InvalidArgument(sprintf('Missing parameter "%s" when validating the "%s". The value cannot be null.', $name, __CLASS__));
            }
        }
        if ($this->Delete) {
            $this->Delete->validate();
        }
    }
}
