<?php

namespace AsyncAws\S3\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;
use AsyncAws\S3\Enum\BucketCannedACL;
use AsyncAws\S3\Enum\BucketNamespace;
use AsyncAws\S3\Enum\ObjectOwnership;
use AsyncAws\S3\ValueObject\CreateBucketConfiguration;

final class CreateBucketRequest extends Input
{
    /**
     * The canned ACL to apply to the bucket.
     *
     * > This functionality is not supported for directory buckets.
     *
     * @var BucketCannedACL::*|null
     */
    private $acl;

    /**
     * The name of the bucket to create.
     *
     * **General purpose buckets** - For information about bucket naming restrictions, see Bucket naming rules [^1] in the
     * *Amazon S3 User Guide*.
     *
     * **Directory buckets ** - When you use this operation with a directory bucket, you must use path-style requests in the
     * format `https://s3express-control.*region-code*.amazonaws.com/*bucket-name*`. Virtual-hosted-style requests aren't
     * supported. Directory bucket names must be unique in the chosen Zone (Availability Zone or Local Zone). Bucket names
     * must also follow the format `*bucket-base-name*--*zone-id*--x-s3` (for example,
     * `*DOC-EXAMPLE-BUCKET*--*usw2-az1*--x-s3`). For information about bucket naming restrictions, see Directory bucket
     * naming rules [^2] in the *Amazon S3 User Guide*
     *
     * [^1]: https://docs.aws.amazon.com/AmazonS3/latest/userguide/bucketnamingrules.html
     * [^2]: https://docs.aws.amazon.com/AmazonS3/latest/userguide/directory-bucket-naming-rules.html
     *
     * @required
     *
     * @var string|null
     */
    private $bucket;

    /**
     * The configuration information for the bucket.
     *
     * @var CreateBucketConfiguration|null
     */
    private $createBucketConfiguration;

    /**
     * Allows grantee the read, write, read ACP, and write ACP permissions on the bucket.
     *
     * > This functionality is not supported for directory buckets.
     *
     * @var string|null
     */
    private $grantFullControl;

    /**
     * Allows grantee to list the objects in the bucket.
     *
     * > This functionality is not supported for directory buckets.
     *
     * @var string|null
     */
    private $grantRead;

    /**
     * Allows grantee to read the bucket ACL.
     *
     * > This functionality is not supported for directory buckets.
     *
     * @var string|null
     */
    private $grantReadAcp;

    /**
     * Allows grantee to create new objects in the bucket.
     *
     * For the bucket and object owners of existing objects, also allows deletions and overwrites of those objects.
     *
     * > This functionality is not supported for directory buckets.
     *
     * @var string|null
     */
    private $grantWrite;

    /**
     * Allows grantee to write the ACL for the applicable bucket.
     *
     * > This functionality is not supported for directory buckets.
     *
     * @var string|null
     */
    private $grantWriteAcp;

    /**
     * Specifies whether you want S3 Object Lock to be enabled for the new bucket.
     *
     * > This functionality is not supported for directory buckets.
     *
     * @var bool|null
     */
    private $objectLockEnabledForBucket;

    /**
     * @var ObjectOwnership::*|null
     */
    private $objectOwnership;

    /**
     * Specifies the namespace where you want to create your general purpose bucket. When you create a general purpose
     * bucket, you can choose to create a bucket in the shared global namespace or you can choose to create a bucket in your
     * account regional namespace. Your account regional namespace is a subdivision of the global namespace that only your
     * account can create buckets in. For more information on bucket namespaces, see Namespaces for general purpose buckets
     * [^1].
     *
     * General purpose buckets in your account regional namespace must follow a specific naming convention. These buckets
     * consist of a bucket name prefix that you create, and a suffix that contains your 12-digit Amazon Web Services Account
     * ID, the Amazon Web Services Region code, and ends with `-an`. Bucket names must follow the format
     * `bucket-name-prefix-accountId-region-an` (for example, `amzn-s3-demo-bucket-111122223333-us-west-2-an`). For
     * information about bucket naming restrictions, see Account regional namespace naming rules [^2] in the *Amazon S3 User
     * Guide*.
     *
     * > This functionality is not supported for directory buckets.
     *
     * [^1]: https://docs.aws.amazon.com/AmazonS3/latest/userguide/gpbucketnamespaces.html
     * [^2]: https://docs.aws.amazon.com/AmazonS3/latest/userguide/bucketnamingrules.html#account-regional-naming-rules
     *
     * @var BucketNamespace::*|null
     */
    private $bucketNamespace;

    /**
     * @param array{
     *   ACL?: BucketCannedACL::*|null,
     *   Bucket?: string,
     *   CreateBucketConfiguration?: CreateBucketConfiguration|array|null,
     *   GrantFullControl?: string|null,
     *   GrantRead?: string|null,
     *   GrantReadACP?: string|null,
     *   GrantWrite?: string|null,
     *   GrantWriteACP?: string|null,
     *   ObjectLockEnabledForBucket?: bool|null,
     *   ObjectOwnership?: ObjectOwnership::*|null,
     *   BucketNamespace?: BucketNamespace::*|null,
     *   '@region'?: string|null,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->acl = $input['ACL'] ?? null;
        $this->bucket = $input['Bucket'] ?? null;
        $this->createBucketConfiguration = isset($input['CreateBucketConfiguration']) ? CreateBucketConfiguration::create($input['CreateBucketConfiguration']) : null;
        $this->grantFullControl = $input['GrantFullControl'] ?? null;
        $this->grantRead = $input['GrantRead'] ?? null;
        $this->grantReadAcp = $input['GrantReadACP'] ?? null;
        $this->grantWrite = $input['GrantWrite'] ?? null;
        $this->grantWriteAcp = $input['GrantWriteACP'] ?? null;
        $this->objectLockEnabledForBucket = $input['ObjectLockEnabledForBucket'] ?? null;
        $this->objectOwnership = $input['ObjectOwnership'] ?? null;
        $this->bucketNamespace = $input['BucketNamespace'] ?? null;
        parent::__construct($input);
    }

    /**
     * @param array{
     *   ACL?: BucketCannedACL::*|null,
     *   Bucket?: string,
     *   CreateBucketConfiguration?: CreateBucketConfiguration|array|null,
     *   GrantFullControl?: string|null,
     *   GrantRead?: string|null,
     *   GrantReadACP?: string|null,
     *   GrantWrite?: string|null,
     *   GrantWriteACP?: string|null,
     *   ObjectLockEnabledForBucket?: bool|null,
     *   ObjectOwnership?: ObjectOwnership::*|null,
     *   BucketNamespace?: BucketNamespace::*|null,
     *   '@region'?: string|null,
     * }|CreateBucketRequest $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return BucketCannedACL::*|null
     */
    public function getAcl(): ?string
    {
        return $this->acl;
    }

    public function getBucket(): ?string
    {
        return $this->bucket;
    }

    /**
     * @return BucketNamespace::*|null
     */
    public function getBucketNamespace(): ?string
    {
        return $this->bucketNamespace;
    }

    public function getCreateBucketConfiguration(): ?CreateBucketConfiguration
    {
        return $this->createBucketConfiguration;
    }

    public function getGrantFullControl(): ?string
    {
        return $this->grantFullControl;
    }

    public function getGrantRead(): ?string
    {
        return $this->grantRead;
    }

    public function getGrantReadAcp(): ?string
    {
        return $this->grantReadAcp;
    }

    public function getGrantWrite(): ?string
    {
        return $this->grantWrite;
    }

    public function getGrantWriteAcp(): ?string
    {
        return $this->grantWriteAcp;
    }

    public function getObjectLockEnabledForBucket(): ?bool
    {
        return $this->objectLockEnabledForBucket;
    }

    /**
     * @return ObjectOwnership::*|null
     */
    public function getObjectOwnership(): ?string
    {
        return $this->objectOwnership;
    }

    /**
     * @internal
     */
    public function request(): Request
    {
        // Prepare headers
        $headers = ['content-type' => 'application/xml'];
        if (null !== $this->acl) {
            if (!BucketCannedACL::exists($this->acl)) {
                /** @psalm-suppress NoValue */
                throw new InvalidArgument(\sprintf('Invalid parameter "ACL" for "%s". The value "%s" is not a valid "BucketCannedACL".', __CLASS__, $this->acl));
            }
            $headers['x-amz-acl'] = $this->acl;
        }
        if (null !== $this->grantFullControl) {
            $headers['x-amz-grant-full-control'] = $this->grantFullControl;
        }
        if (null !== $this->grantRead) {
            $headers['x-amz-grant-read'] = $this->grantRead;
        }
        if (null !== $this->grantReadAcp) {
            $headers['x-amz-grant-read-acp'] = $this->grantReadAcp;
        }
        if (null !== $this->grantWrite) {
            $headers['x-amz-grant-write'] = $this->grantWrite;
        }
        if (null !== $this->grantWriteAcp) {
            $headers['x-amz-grant-write-acp'] = $this->grantWriteAcp;
        }
        if (null !== $this->objectLockEnabledForBucket) {
            $headers['x-amz-bucket-object-lock-enabled'] = $this->objectLockEnabledForBucket ? 'true' : 'false';
        }
        if (null !== $this->objectOwnership) {
            if (!ObjectOwnership::exists($this->objectOwnership)) {
                /** @psalm-suppress NoValue */
                throw new InvalidArgument(\sprintf('Invalid parameter "ObjectOwnership" for "%s". The value "%s" is not a valid "ObjectOwnership".', __CLASS__, $this->objectOwnership));
            }
            $headers['x-amz-object-ownership'] = $this->objectOwnership;
        }
        if (null !== $this->bucketNamespace) {
            if (!BucketNamespace::exists($this->bucketNamespace)) {
                /** @psalm-suppress NoValue */
                throw new InvalidArgument(\sprintf('Invalid parameter "BucketNamespace" for "%s". The value "%s" is not a valid "BucketNamespace".', __CLASS__, $this->bucketNamespace));
            }
            $headers['x-amz-bucket-namespace'] = $this->bucketNamespace;
        }

        // Prepare query
        $query = [];

        // Prepare URI
        $uri = [];
        if (null === $v = $this->bucket) {
            throw new InvalidArgument(\sprintf('Missing parameter "Bucket" for "%s". The value cannot be null.', __CLASS__));
        }
        $uri['Bucket'] = $v;
        $uriString = '/' . rawurlencode($uri['Bucket']);

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
    public function setAcl(?string $value): self
    {
        $this->acl = $value;

        return $this;
    }

    public function setBucket(?string $value): self
    {
        $this->bucket = $value;

        return $this;
    }

    /**
     * @param BucketNamespace::*|null $value
     */
    public function setBucketNamespace(?string $value): self
    {
        $this->bucketNamespace = $value;

        return $this;
    }

    public function setCreateBucketConfiguration(?CreateBucketConfiguration $value): self
    {
        $this->createBucketConfiguration = $value;

        return $this;
    }

    public function setGrantFullControl(?string $value): self
    {
        $this->grantFullControl = $value;

        return $this;
    }

    public function setGrantRead(?string $value): self
    {
        $this->grantRead = $value;

        return $this;
    }

    public function setGrantReadAcp(?string $value): self
    {
        $this->grantReadAcp = $value;

        return $this;
    }

    public function setGrantWrite(?string $value): self
    {
        $this->grantWrite = $value;

        return $this;
    }

    public function setGrantWriteAcp(?string $value): self
    {
        $this->grantWriteAcp = $value;

        return $this;
    }

    public function setObjectLockEnabledForBucket(?bool $value): self
    {
        $this->objectLockEnabledForBucket = $value;

        return $this;
    }

    /**
     * @param ObjectOwnership::*|null $value
     */
    public function setObjectOwnership(?string $value): self
    {
        $this->objectOwnership = $value;

        return $this;
    }

    private function requestBody(\DOMNode $node, \DOMDocument $document): void
    {
        if (null !== $v = $this->createBucketConfiguration) {
            $node->appendChild($child = $document->createElement('CreateBucketConfiguration'));
            $child->setAttribute('xmlns', 'http://s3.amazonaws.com/doc/2006-03-01/');
            $v->requestBody($child, $document);
        }
    }
}
