<?php

namespace AsyncAws\S3\Input;

use AsyncAws\Core\Exception\InvalidArgument;

class CreateBucketRequest
{
    /**
     * @var string|null
     */
    private $ACL;

    /**
     * @required
     *
     * @var string|null
     */
    private $Bucket;

    /**
     * @var CreateBucketConfiguration|null
     */
    private $CreateBucketConfiguration;

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
     * @var bool|null
     */
    private $ObjectLockEnabledForBucket;

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @see http://docs.amazonwebservices.com/AmazonS3/latest/API/RESTBucketPUT.html
     *
     * @param array{
     *   ACL?: string,
     *   Bucket: string,
     *   CreateBucketConfiguration?: \AsyncAws\S3\Input\CreateBucketConfiguration|array,
     *   GrantFullControl?: string,
     *   GrantRead?: string,
     *   GrantReadACP?: string,
     *   GrantWrite?: string,
     *   GrantWriteACP?: string,
     *   ObjectLockEnabledForBucket?: bool,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->ACL = $input['ACL'] ?? null;
        $this->Bucket = $input['Bucket'] ?? null;
        $this->CreateBucketConfiguration = isset($input['CreateBucketConfiguration']) ? CreateBucketConfiguration::create($input['CreateBucketConfiguration']) : null;
        $this->GrantFullControl = $input['GrantFullControl'] ?? null;
        $this->GrantRead = $input['GrantRead'] ?? null;
        $this->GrantReadACP = $input['GrantReadACP'] ?? null;
        $this->GrantWrite = $input['GrantWrite'] ?? null;
        $this->GrantWriteACP = $input['GrantWriteACP'] ?? null;
        $this->ObjectLockEnabledForBucket = $input['ObjectLockEnabledForBucket'] ?? null;
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

    public function getBucket(): ?string
    {
        return $this->Bucket;
    }

    public function setBucket(?string $value): self
    {
        $this->Bucket = $value;

        return $this;
    }

    public function getCreateBucketConfiguration(): ?CreateBucketConfiguration
    {
        return $this->CreateBucketConfiguration;
    }

    public function setCreateBucketConfiguration(?CreateBucketConfiguration $value): self
    {
        $this->CreateBucketConfiguration = $value;

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

    public function getObjectLockEnabledForBucket(): ?bool
    {
        return $this->ObjectLockEnabledForBucket;
    }

    public function setObjectLockEnabledForBucket(?bool $value): self
    {
        $this->ObjectLockEnabledForBucket = $value;

        return $this;
    }

    public function requestHeaders(): array
    {
        $headers = [];
        if (null !== $this->ACL) {
            $headers['x-amz-acl'] = $this->ACL;
        }
        if (null !== $this->GrantFullControl) {
            $headers['x-amz-grant-full-control'] = $this->GrantFullControl;
        }
        if (null !== $this->GrantRead) {
            $headers['x-amz-grant-read'] = $this->GrantRead;
        }
        if (null !== $this->GrantReadACP) {
            $headers['x-amz-grant-read-acp'] = $this->GrantReadACP;
        }
        if (null !== $this->GrantWrite) {
            $headers['x-amz-grant-write'] = $this->GrantWrite;
        }
        if (null !== $this->GrantWriteACP) {
            $headers['x-amz-grant-write-acp'] = $this->GrantWriteACP;
        }
        if (null !== $this->ObjectLockEnabledForBucket) {
            $headers['x-amz-bucket-object-lock-enabled'] = $this->ObjectLockEnabledForBucket;
        }

        return $headers;
    }

    public function requestQuery(): array
    {
        $query = [];

        return $query;
    }

    public function requestBody(): array
    {
        $payload = ['Action' => 'CreateBucket', 'Version' => '2006-03-01'];
        if (null !== $this->CreateBucketConfiguration) {
            $payload['CreateBucketConfiguration'] = $this->CreateBucketConfiguration;
        }

        return $payload;
    }

    public function requestUri(): string
    {
        $uri = [];
        $uri['Bucket'] = $this->Bucket ?? '';

        return "/{$uri['Bucket']}";
    }

    public function validate(): void
    {
        foreach (['Bucket'] as $name) {
            if (null === $this->$name) {
                throw new InvalidArgument(sprintf('Missing parameter "%s" when validating the "%s". The value cannot be null.', $name, __CLASS__));
            }
        }
        if ($this->CreateBucketConfiguration) {
            $this->CreateBucketConfiguration->validate();
        }
    }
}
