<?php

namespace AsyncAws\S3\Input;

class PutObjectAclRequest
{
    /**
     * @var string|null
     */
    private $ACL;

    /**
     * @var AccessControlPolicy|null
     */
    private $AccessControlPolicy;

    /**
     * @required
     *
     * @var string|null
     */
    private $Bucket;

    /**
     * @var string|null
     */
    private $ContentMD5;

    /**
     * @var string|null
     */
    private $GrantFullControl;

    /**
     * @var string|null
     */
    private $GrantRead;

    /**
     * @var string|null
     */
    private $GrantReadACP;

    /**
     * @var string|null
     */
    private $GrantWrite;

    /**
     * @var string|null
     */
    private $GrantWriteACP;

    /**
     * @required
     *
     * @var string|null
     */
    private $Key;

    /**
     * @var string|null
     */
    private $RequestPayer;

    /**
     * @var string|null
     */
    private $VersionId;

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @see http://docs.amazonwebservices.com/AmazonS3/latest/API/RESTObjectPUTacl.html
     *
     * @param array{
     *   ACL?: string,
     *   AccessControlPolicy?: \AsyncAws\S3\Input\AccessControlPolicy|array,
     *   Bucket: string,
     *   ContentMD5?: string,
     *   GrantFullControl?: string,
     *   GrantRead?: string,
     *   GrantReadACP?: string,
     *   GrantWrite?: string,
     *   GrantWriteACP?: string,
     *   Key: string,
     *   RequestPayer?: string,
     *   VersionId?: string,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->ACL = $input["ACL"] ?? null;
        $this->AccessControlPolicy = isset($input["AccessControlPolicy"]) ? AccessControlPolicy::create($input["AccessControlPolicy"]) : null;
        $this->Bucket = $input["Bucket"] ?? null;
        $this->ContentMD5 = $input["ContentMD5"] ?? null;
        $this->GrantFullControl = $input["GrantFullControl"] ?? null;
        $this->GrantRead = $input["GrantRead"] ?? null;
        $this->GrantReadACP = $input["GrantReadACP"] ?? null;
        $this->GrantWrite = $input["GrantWrite"] ?? null;
        $this->GrantWriteACP = $input["GrantWriteACP"] ?? null;
        $this->Key = $input["Key"] ?? null;
        $this->RequestPayer = $input["RequestPayer"] ?? null;
        $this->VersionId = $input["VersionId"] ?? null;
    }

    public function getACL(): ?string
    {
        return $this->ACL;
    }

    public function setACL(?string $value): self
    {
        $this->ACL = $value;

        return $this;
    }

    public function getAccessControlPolicy(): ?AccessControlPolicy
    {
        return $this->AccessControlPolicy;
    }

    public function setAccessControlPolicy(?AccessControlPolicy $value): self
    {
        $this->AccessControlPolicy = $value;

        return $this;
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

    public function getContentMD5(): ?string
    {
        return $this->ContentMD5;
    }

    public function setContentMD5(?string $value): self
    {
        $this->ContentMD5 = $value;

        return $this;
    }

    public function getGrantFullControl(): ?string
    {
        return $this->GrantFullControl;
    }

    public function setGrantFullControl(?string $value): self
    {
        $this->GrantFullControl = $value;

        return $this;
    }

    public function getGrantRead(): ?string
    {
        return $this->GrantRead;
    }

    public function setGrantRead(?string $value): self
    {
        $this->GrantRead = $value;

        return $this;
    }

    public function getGrantReadACP(): ?string
    {
        return $this->GrantReadACP;
    }

    public function setGrantReadACP(?string $value): self
    {
        $this->GrantReadACP = $value;

        return $this;
    }

    public function getGrantWrite(): ?string
    {
        return $this->GrantWrite;
    }

    public function setGrantWrite(?string $value): self
    {
        $this->GrantWrite = $value;

        return $this;
    }

    public function getGrantWriteACP(): ?string
    {
        return $this->GrantWriteACP;
    }

    public function setGrantWriteACP(?string $value): self
    {
        $this->GrantWriteACP = $value;

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

    public function getRequestPayer(): ?string
    {
        return $this->RequestPayer;
    }

    public function setRequestPayer(?string $value): self
    {
        $this->RequestPayer = $value;

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

    public function requestHeaders(): array
    {
        $headers = [];
        if (null !== $this->ACL) {
            $headers["x-amz-acl"] = $this->ACL;
        }
        if (null !== $this->ContentMD5) {
            $headers["Content-MD5"] = $this->ContentMD5;
        }
        if (null !== $this->GrantFullControl) {
            $headers["x-amz-grant-full-control"] = $this->GrantFullControl;
        }
        if (null !== $this->GrantRead) {
            $headers["x-amz-grant-read"] = $this->GrantRead;
        }
        if (null !== $this->GrantReadACP) {
            $headers["x-amz-grant-read-acp"] = $this->GrantReadACP;
        }
        if (null !== $this->GrantWrite) {
            $headers["x-amz-grant-write"] = $this->GrantWrite;
        }
        if (null !== $this->GrantWriteACP) {
            $headers["x-amz-grant-write-acp"] = $this->GrantWriteACP;
        }
        if (null !== $this->RequestPayer) {
            $headers["x-amz-request-payer"] = $this->RequestPayer;
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

    public function requestBody(): array
    {
        $payload = [];
        if (null !== $this->AccessControlPolicy) {
            $payload["AccessControlPolicy"] = $this->AccessControlPolicy;
        }

        return $payload;
    }

    public function requestUri(): string
    {
        $uri = [];
        $uri['Bucket'] = $this->Bucket ?? '';
        $uri['Key'] = $this->Key ?? '';

        return "/{$uri['Bucket']}/{$uri['Key']}?acl";
    }
}
