<?php

namespace AsyncAws\S3\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Result;
use AsyncAws\S3\Enum\RequestCharged;
use AsyncAws\S3\Enum\ServerSideEncryption;

class CompleteMultipartUploadOutput extends Result
{
    /**
     * The URI that identifies the newly created object.
     */
    private $Location;

    /**
     * The name of the bucket that contains the newly created object.
     */
    private $Bucket;

    /**
     * The object key of the newly created object.
     */
    private $Key;

    /**
     * If the object expiration is configured, this will contain the expiration date (expiry-date) and rule ID (rule-id).
     * The value of rule-id is URL encoded.
     */
    private $Expiration;

    /**
     * Entity tag that identifies the newly created object's data. Objects with different object data will have different
     * entity tags. The entity tag is an opaque string. The entity tag may or may not be an MD5 digest of the object data.
     * If the entity tag is not an MD5 digest of the object data, it will contain one or more nonhexadecimal characters
     * and/or will consist of less than 32 or more than 32 hexadecimal digits.
     */
    private $ETag;

    /**
     * If you specified server-side encryption either with an Amazon S3-managed encryption key or an AWS KMS customer master
     * key (CMK) in your initiate multipart upload request, the response includes this header. It confirms the encryption
     * algorithm that Amazon S3 used to encrypt the object.
     */
    private $ServerSideEncryption;

    /**
     * Version ID of the newly created object, in case the bucket has versioning turned on.
     */
    private $VersionId;

    /**
     * If present, specifies the ID of the AWS Key Management Service (AWS KMS) symmetric customer managed customer master
     * key (CMK) that was used for the object.
     */
    private $SSEKMSKeyId;

    /**
     * Indicates whether the multipart upload uses an S3 Bucket Key for server-side encryption with AWS KMS (SSE-KMS).
     */
    private $BucketKeyEnabled;

    private $RequestCharged;

    public function getBucket(): ?string
    {
        $this->initialize();

        return $this->Bucket;
    }

    public function getBucketKeyEnabled(): ?bool
    {
        $this->initialize();

        return $this->BucketKeyEnabled;
    }

    public function getETag(): ?string
    {
        $this->initialize();

        return $this->ETag;
    }

    public function getExpiration(): ?string
    {
        $this->initialize();

        return $this->Expiration;
    }

    public function getKey(): ?string
    {
        $this->initialize();

        return $this->Key;
    }

    public function getLocation(): ?string
    {
        $this->initialize();

        return $this->Location;
    }

    /**
     * @return RequestCharged::*|null
     */
    public function getRequestCharged(): ?string
    {
        $this->initialize();

        return $this->RequestCharged;
    }

    public function getSSEKMSKeyId(): ?string
    {
        $this->initialize();

        return $this->SSEKMSKeyId;
    }

    /**
     * @return ServerSideEncryption::*|null
     */
    public function getServerSideEncryption(): ?string
    {
        $this->initialize();

        return $this->ServerSideEncryption;
    }

    public function getVersionId(): ?string
    {
        $this->initialize();

        return $this->VersionId;
    }

    protected function populateResult(Response $response): void
    {
        $headers = $response->getHeaders();

        $this->Expiration = $headers['x-amz-expiration'][0] ?? null;
        $this->ServerSideEncryption = $headers['x-amz-server-side-encryption'][0] ?? null;
        $this->VersionId = $headers['x-amz-version-id'][0] ?? null;
        $this->SSEKMSKeyId = $headers['x-amz-server-side-encryption-aws-kms-key-id'][0] ?? null;
        $this->BucketKeyEnabled = isset($headers['x-amz-server-side-encryption-bucket-key-enabled'][0]) ? filter_var($headers['x-amz-server-side-encryption-bucket-key-enabled'][0], \FILTER_VALIDATE_BOOLEAN) : null;
        $this->RequestCharged = $headers['x-amz-request-charged'][0] ?? null;

        $data = new \SimpleXMLElement($response->getContent());
        $this->Location = ($v = $data->Location) ? (string) $v : null;
        $this->Bucket = ($v = $data->Bucket) ? (string) $v : null;
        $this->Key = ($v = $data->Key) ? (string) $v : null;
        $this->ETag = ($v = $data->ETag) ? (string) $v : null;
    }
}
