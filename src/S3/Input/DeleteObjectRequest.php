<?php

namespace AsyncAws\S3\Input;

class DeleteObjectRequest
{
    /**
     * @required
     *
     * @var string|null
     */
    private $Bucket;

    /**
     * @required
     *
     * @var string|null
     */
    private $Key;

    /**
     * @var string|null
     */
    private $MFA;

    /**
     * @var string|null
     */
    private $VersionId;

    /**
     * @var string|null
     */
    private $RequestPayer;

    /**
     * @var bool|null
     */
    private $BypassGovernanceRetention;

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

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
    public function __construct(array $input = [])
    {
        $this->Bucket = $input["Bucket"] ?? null;
        $this->Key = $input["Key"] ?? null;
        $this->MFA = $input["MFA"] ?? null;
        $this->VersionId = $input["VersionId"] ?? null;
        $this->RequestPayer = $input["RequestPayer"] ?? null;
        $this->BypassGovernanceRetention = $input["BypassGovernanceRetention"] ?? null;
    }

    public function getBucket(): ?string
    {
        return $this->Bucket;
    }

    public function setBucket(?string $value): self
    {
        $this->Bucket = $value;

        return $this;
    }

    public function getKey(): ?string
    {
        return $this->Key;
    }

    public function setKey(?string $value): self
    {
        $this->Key = $value;

        return $this;
    }

    public function getMFA(): ?string
    {
        return $this->MFA;
    }

    public function setMFA(?string $value): self
    {
        $this->MFA = $value;

        return $this;
    }

    public function getVersionId(): ?string
    {
        return $this->VersionId;
    }

    public function setVersionId(?string $value): self
    {
        $this->VersionId = $value;

        return $this;
    }

    public function getRequestPayer(): ?string
    {
        return $this->RequestPayer;
    }

    public function setRequestPayer(?string $value): self
    {
        $this->RequestPayer = $value;

        return $this;
    }

    public function getBypassGovernanceRetention(): ?bool
    {
        return $this->BypassGovernanceRetention;
    }

    public function setBypassGovernanceRetention(?bool $value): self
    {
        $this->BypassGovernanceRetention = $value;

        return $this;
    }

    public function requestHeaders(): array
    {
        $headers = [];
        if (null !== $this->MFA) {
            $headers["x-amz-mfa"] = $this->MFA;
        }
        if (null !== $this->RequestPayer) {
            $headers["x-amz-request-payer"] = $this->RequestPayer;
        }
        if (null !== $this->BypassGovernanceRetention) {
            $headers["x-amz-bypass-governance-retention"] = $this->BypassGovernanceRetention;
        }

        return $headers;
    }

    public function requestQuery(): array
    {
        $query = [];
        if (null !== $this->VersionId) {
            $query["versionId"] = $this->VersionId;
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
}
