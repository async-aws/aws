<?php

namespace AsyncAws\S3\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;
use AsyncAws\S3\Enum\BucketCannedACL;
use AsyncAws\S3\ValueObject\CreateBucketConfiguration;

class CreateBucketRequest
{
    /**
     * The canned ACL to apply to the bucket.
     *
     * @var BucketCannedACL::*|null
     */
    private $ACL;

    /**
     * The name of the bucket to create.
     *
     * @required
     *
     * @var string|null
     */
    private $Bucket;

    /**
     * The configuration information for the bucket.
     *
     * @var CreateBucketConfiguration|null
     */
    private $CreateBucketConfiguration;

    /**
     * Allows grantee the read, write, read ACP, and write ACP permissions on the bucket.
     *
     * @var string|null
     */
    private $GrantFullControl;

    /**
     * Allows grantee to list the objects in the bucket.
     *
     * @var string|null
     */
    private $GrantRead;

    /**
     * Allows grantee to read the bucket ACL.
     *
     * @var string|null
     */
    private $GrantReadACP;

    /**
     * Allows grantee to create, overwrite, and delete any object in the bucket.
     *
     * @var string|null
     */
    private $GrantWrite;

    /**
     * Allows grantee to write the ACL for the applicable bucket.
     *
     * @var string|null
     */
    private $GrantWriteACP;

    /**
     * Specifies whether you want S3 Object Lock to be enabled for the new bucket.
     *
     * @var bool|null
     */
    private $ObjectLockEnabledForBucket;

    /**
     * @see http://docs.amazonwebservices.com/AmazonS3/latest/API/RESTBucketPUT.html
     *
     * @param array{
     *   ACL?: \AsyncAws\S3\Enum\BucketCannedACL::*,
     *   Bucket?: string,
     *   CreateBucketConfiguration?: \AsyncAws\S3\ValueObject\CreateBucketConfiguration|array,
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

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return BucketCannedACL::*|null
     */
    public function getACL(): ?string
    {
        return $this->ACL;
    }

    public function getBucket(): ?string
    {
        return $this->Bucket;
    }

    public function getCreateBucketConfiguration(): ?CreateBucketConfiguration
    {
        return $this->CreateBucketConfiguration;
    }

    public function getGrantFullControl(): ?string
    {
        return $this->GrantFullControl;
    }

    public function getGrantRead(): ?string
    {
        return $this->GrantRead;
    }

    public function getGrantReadACP(): ?string
    {
        return $this->GrantReadACP;
    }

    public function getGrantWrite(): ?string
    {
        return $this->GrantWrite;
    }

    public function getGrantWriteACP(): ?string
    {
        return $this->GrantWriteACP;
    }

    public function getObjectLockEnabledForBucket(): ?bool
    {
        return $this->ObjectLockEnabledForBucket;
    }

    /**
     * @internal
     */
    public function request(): Request
    {
        // Prepare headers
        $headers = ['content-type' => 'application/xml'];
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
            $headers['x-amz-bucket-object-lock-enabled'] = $this->ObjectLockEnabledForBucket ? 'true' : 'false';
        }

        // Prepare query
        $query = [];

        // Prepare URI
        $uri = [];
        $uri['Bucket'] = $this->Bucket ?? '';
        $uriString = "/{$uri['Bucket']}";

        // Prepare Body

        $document = new \DOMDocument('1.0', 'UTF-8');
        $document->formatOutput = false;
        $this->requestBody($document, $document);
        $body = $document->hasChildNodes() ? $document->saveXML() : '';

        // Return the Request
        return new Request('PUT', $uriString, $query, $headers, StreamFactory::create($body));
    }

    /**
     * @param BucketCannedACL::*|null $value
     */
    public function setACL(?string $value): self
    {
        $this->ACL = $value;

        return $this;
    }

    public function setBucket(?string $value): self
    {
        $this->Bucket = $value;

        return $this;
    }

    public function setCreateBucketConfiguration(?CreateBucketConfiguration $value): self
    {
        $this->CreateBucketConfiguration = $value;

        return $this;
    }

    public function setGrantFullControl(?string $value): self
    {
        $this->GrantFullControl = $value;

        return $this;
    }

    public function setGrantRead(?string $value): self
    {
        $this->GrantRead = $value;

        return $this;
    }

    public function setGrantReadACP(?string $value): self
    {
        $this->GrantReadACP = $value;

        return $this;
    }

    public function setGrantWrite(?string $value): self
    {
        $this->GrantWrite = $value;

        return $this;
    }

    public function setGrantWriteACP(?string $value): self
    {
        $this->GrantWriteACP = $value;

        return $this;
    }

    public function setObjectLockEnabledForBucket(?bool $value): self
    {
        $this->ObjectLockEnabledForBucket = $value;

        return $this;
    }

    public function validate(): void
    {
        if (null !== $this->ACL) {
            if (!BucketCannedACL::exists($this->ACL)) {
                throw new InvalidArgument(sprintf('Invalid parameter "ACL" when validating the "%s". The value "%s" is not a valid "BucketCannedACL".', __CLASS__, $this->ACL));
            }
        }

        if (null === $this->Bucket) {
            throw new InvalidArgument(sprintf('Missing parameter "Bucket" when validating the "%s". The value cannot be null.', __CLASS__));
        }

        if (null !== $this->CreateBucketConfiguration) {
            $this->CreateBucketConfiguration->validate();
        }
    }

    /**
     * @internal
     */
    private function requestBody(\DomNode $node, \DomDocument $document): void
    {
        if (null !== $v = $this->CreateBucketConfiguration) {
            $node->appendChild($child = $document->createElement('CreateBucketConfiguration'));
            $child->setAttribute('xmlns', 'http://s3.amazonaws.com/doc/2006-03-01/');

            $v->requestBody($child, $document);
        }
    }
}
