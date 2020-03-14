<?php

namespace AsyncAws\S3;

use AsyncAws\Core\AbstractApi;
use AsyncAws\S3\Input\CopyObjectRequest;
use AsyncAws\S3\Input\CreateBucketRequest;
use AsyncAws\S3\Input\DeleteObjectRequest;
use AsyncAws\S3\Input\DeleteObjectsRequest;
use AsyncAws\S3\Input\GetObjectAclRequest;
use AsyncAws\S3\Input\GetObjectRequest;
use AsyncAws\S3\Input\HeadObjectRequest;
use AsyncAws\S3\Input\ListObjectsV2Request;
use AsyncAws\S3\Input\PutObjectAclRequest;
use AsyncAws\S3\Input\PutObjectRequest;
use AsyncAws\S3\Result\CopyObjectOutput;
use AsyncAws\S3\Result\CreateBucketOutput;
use AsyncAws\S3\Result\DeleteObjectOutput;
use AsyncAws\S3\Result\DeleteObjectsOutput;
use AsyncAws\S3\Result\GetObjectAclOutput;
use AsyncAws\S3\Result\GetObjectOutput;
use AsyncAws\S3\Result\HeadObjectOutput;
use AsyncAws\S3\Result\ListObjectsV2Output;
use AsyncAws\S3\Result\PutObjectAclOutput;
use AsyncAws\S3\Result\PutObjectOutput;
use AsyncAws\S3\Signer\SignerV4ForS3;

class S3Client extends AbstractApi
{
    /**
     * Creates a copy of an object that is already stored in Amazon S3.
     *
     * @see http://docs.amazonwebservices.com/AmazonS3/latest/API/RESTObjectCOPY.html
     *
     * @param array{
     *   ACL?: \AsyncAws\S3\Enum\ObjectCannedACL::*,
     *   Bucket: string,
     *   CacheControl?: string,
     *   ContentDisposition?: string,
     *   ContentEncoding?: string,
     *   ContentLanguage?: string,
     *   ContentType?: string,
     *   CopySource: string,
     *   CopySourceIfMatch?: string,
     *   CopySourceIfModifiedSince?: \DateTimeInterface|string,
     *   CopySourceIfNoneMatch?: string,
     *   CopySourceIfUnmodifiedSince?: \DateTimeInterface|string,
     *   Expires?: \DateTimeInterface|string,
     *   GrantFullControl?: string,
     *   GrantRead?: string,
     *   GrantReadACP?: string,
     *   GrantWriteACP?: string,
     *   Key: string,
     *   Metadata?: string[],
     *   MetadataDirective?: \AsyncAws\S3\Enum\MetadataDirective::*,
     *   TaggingDirective?: \AsyncAws\S3\Enum\TaggingDirective::*,
     *   ServerSideEncryption?: \AsyncAws\S3\Enum\ServerSideEncryption::*,
     *   StorageClass?: \AsyncAws\S3\Enum\StorageClass::*,
     *   WebsiteRedirectLocation?: string,
     *   SSECustomerAlgorithm?: string,
     *   SSECustomerKey?: string,
     *   SSECustomerKeyMD5?: string,
     *   SSEKMSKeyId?: string,
     *   SSEKMSEncryptionContext?: string,
     *   CopySourceSSECustomerAlgorithm?: string,
     *   CopySourceSSECustomerKey?: string,
     *   CopySourceSSECustomerKeyMD5?: string,
     *   RequestPayer?: \AsyncAws\S3\Enum\RequestPayer::*,
     *   Tagging?: string,
     *   ObjectLockMode?: \AsyncAws\S3\Enum\ObjectLockMode::*,
     *   ObjectLockRetainUntilDate?: \DateTimeInterface|string,
     *   ObjectLockLegalHoldStatus?: \AsyncAws\S3\Enum\ObjectLockLegalHoldStatus::*,
     * }|CopyObjectRequest $input
     */
    public function copyObject($input): CopyObjectOutput
    {
        $input = CopyObjectRequest::create($input);
        $input->validate();

        $response = $this->getResponse($input->request());

        return new CopyObjectOutput($response, $this->httpClient);
    }

    /**
     * Creates a new bucket. To create a bucket, you must register with Amazon S3 and have a valid AWS Access Key ID to
     * authenticate requests. Anonymous requests are never allowed to create buckets. By creating the bucket, you become the
     * bucket owner.
     *
     * @see http://docs.amazonwebservices.com/AmazonS3/latest/API/RESTBucketPUT.html
     *
     * @param array{
     *   ACL?: \AsyncAws\S3\Enum\BucketCannedACL::*,
     *   Bucket: string,
     *   CreateBucketConfiguration?: \AsyncAws\S3\Input\CreateBucketConfiguration|array,
     *   GrantFullControl?: string,
     *   GrantRead?: string,
     *   GrantReadACP?: string,
     *   GrantWrite?: string,
     *   GrantWriteACP?: string,
     *   ObjectLockEnabledForBucket?: bool,
     * }|CreateBucketRequest $input
     */
    public function createBucket($input): CreateBucketOutput
    {
        $input = CreateBucketRequest::create($input);
        $input->validate();

        $response = $this->getResponse($input->request());

        return new CreateBucketOutput($response, $this->httpClient);
    }

    /**
     * Removes the null version (if there is one) of an object and inserts a delete marker, which becomes the latest version
     * of the object. If there isn't a null version, Amazon S3 does not remove any objects.
     *
     * @see http://docs.amazonwebservices.com/AmazonS3/latest/API/RESTObjectDELETE.html
     *
     * @param array{
     *   Bucket: string,
     *   Key: string,
     *   MFA?: string,
     *   VersionId?: string,
     *   RequestPayer?: \AsyncAws\S3\Enum\RequestPayer::*,
     *   BypassGovernanceRetention?: bool,
     * }|DeleteObjectRequest $input
     */
    public function deleteObject($input): DeleteObjectOutput
    {
        $input = DeleteObjectRequest::create($input);
        $input->validate();

        $response = $this->getResponse($input->request());

        return new DeleteObjectOutput($response, $this->httpClient);
    }

    /**
     * This operation enables you to delete multiple objects from a bucket using a single HTTP request. If you know the
     * object keys that you want to delete, then this operation provides a suitable alternative to sending individual delete
     * requests, reducing per-request overhead.
     *
     * @see http://docs.amazonwebservices.com/AmazonS3/latest/API/multiobjectdeleteapi.html
     *
     * @param array{
     *   Bucket: string,
     *   Delete: \AsyncAws\S3\Input\Delete|array,
     *   MFA?: string,
     *   RequestPayer?: \AsyncAws\S3\Enum\RequestPayer::*,
     *   BypassGovernanceRetention?: bool,
     * }|DeleteObjectsRequest $input
     */
    public function deleteObjects($input): DeleteObjectsOutput
    {
        $input = DeleteObjectsRequest::create($input);
        $input->validate();

        $response = $this->getResponse($input->request());

        return new DeleteObjectsOutput($response, $this->httpClient);
    }

    /**
     * Retrieves objects from Amazon S3. To use `GET`, you must have `READ` access to the object. If you grant `READ` access
     * to the anonymous user, you can return the object without using an authorization header.
     *
     * @see http://docs.amazonwebservices.com/AmazonS3/latest/API/RESTObjectGET.html
     *
     * @param array{
     *   Bucket: string,
     *   IfMatch?: string,
     *   IfModifiedSince?: \DateTimeInterface|string,
     *   IfNoneMatch?: string,
     *   IfUnmodifiedSince?: \DateTimeInterface|string,
     *   Key: string,
     *   Range?: string,
     *   ResponseCacheControl?: string,
     *   ResponseContentDisposition?: string,
     *   ResponseContentEncoding?: string,
     *   ResponseContentLanguage?: string,
     *   ResponseContentType?: string,
     *   ResponseExpires?: \DateTimeInterface|string,
     *   VersionId?: string,
     *   SSECustomerAlgorithm?: string,
     *   SSECustomerKey?: string,
     *   SSECustomerKeyMD5?: string,
     *   RequestPayer?: \AsyncAws\S3\Enum\RequestPayer::*,
     *   PartNumber?: int,
     * }|GetObjectRequest $input
     */
    public function getObject($input): GetObjectOutput
    {
        $input = GetObjectRequest::create($input);
        $input->validate();

        $response = $this->getResponse($input->request());

        return new GetObjectOutput($response, $this->httpClient);
    }

    /**
     * Returns the access control list (ACL) of an object. To use this operation, you must have READ_ACP access to the
     * object.
     *
     * @see http://docs.amazonwebservices.com/AmazonS3/latest/API/RESTObjectGETacl.html
     *
     * @param array{
     *   Bucket: string,
     *   Key: string,
     *   VersionId?: string,
     *   RequestPayer?: \AsyncAws\S3\Enum\RequestPayer::*,
     * }|GetObjectAclRequest $input
     */
    public function getObjectAcl($input): GetObjectAclOutput
    {
        $input = GetObjectAclRequest::create($input);
        $input->validate();

        $response = $this->getResponse($input->request());

        return new GetObjectAclOutput($response, $this->httpClient);
    }

    /**
     * The HEAD operation retrieves metadata from an object without returning the object itself. This operation is useful if
     * you're only interested in an object's metadata. To use HEAD, you must have READ access to the object.
     *
     * @see http://docs.amazonwebservices.com/AmazonS3/latest/API/RESTObjectHEAD.html
     *
     * @param array{
     *   Bucket: string,
     *   IfMatch?: string,
     *   IfModifiedSince?: \DateTimeInterface|string,
     *   IfNoneMatch?: string,
     *   IfUnmodifiedSince?: \DateTimeInterface|string,
     *   Key: string,
     *   Range?: string,
     *   VersionId?: string,
     *   SSECustomerAlgorithm?: string,
     *   SSECustomerKey?: string,
     *   SSECustomerKeyMD5?: string,
     *   RequestPayer?: \AsyncAws\S3\Enum\RequestPayer::*,
     *   PartNumber?: int,
     * }|HeadObjectRequest $input
     */
    public function headObject($input): HeadObjectOutput
    {
        $input = HeadObjectRequest::create($input);
        $input->validate();

        $response = $this->getResponse($input->request());

        return new HeadObjectOutput($response, $this->httpClient);
    }

    /**
     * Returns some or all (up to 1,000) of the objects in a bucket. You can use the request parameters as selection
     * criteria to return a subset of the objects in a bucket. A `200 OK` response can contain valid or invalid XML. Make
     * sure to design your application to parse the contents of the response and handle it appropriately.
     *
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-s3-2006-03-01.html#listobjectsv2
     *
     * @param array{
     *   Bucket: string,
     *   Delimiter?: string,
     *   EncodingType?: \AsyncAws\S3\Enum\EncodingType::*,
     *   MaxKeys?: int,
     *   Prefix?: string,
     *   ContinuationToken?: string,
     *   FetchOwner?: bool,
     *   StartAfter?: string,
     *   RequestPayer?: \AsyncAws\S3\Enum\RequestPayer::*,
     * }|ListObjectsV2Request $input
     */
    public function listObjectsV2($input): ListObjectsV2Output
    {
        $input = ListObjectsV2Request::create($input);
        $input->validate();

        $response = $this->getResponse($input->request());

        return new ListObjectsV2Output($response, $this->httpClient, $this, $input);
    }

    /**
     * Adds an object to a bucket. You must have WRITE permissions on a bucket to add an object to it.
     *
     * @see http://docs.amazonwebservices.com/AmazonS3/latest/API/RESTObjectPUT.html
     *
     * @param array{
     *   ACL?: \AsyncAws\S3\Enum\ObjectCannedACL::*,
     *   Body?: string|resource|callable|iterable,
     *   Bucket: string,
     *   CacheControl?: string,
     *   ContentDisposition?: string,
     *   ContentEncoding?: string,
     *   ContentLanguage?: string,
     *   ContentLength?: string,
     *   ContentMD5?: string,
     *   ContentType?: string,
     *   Expires?: \DateTimeInterface|string,
     *   GrantFullControl?: string,
     *   GrantRead?: string,
     *   GrantReadACP?: string,
     *   GrantWriteACP?: string,
     *   Key: string,
     *   Metadata?: string[],
     *   ServerSideEncryption?: \AsyncAws\S3\Enum\ServerSideEncryption::*,
     *   StorageClass?: \AsyncAws\S3\Enum\StorageClass::*,
     *   WebsiteRedirectLocation?: string,
     *   SSECustomerAlgorithm?: string,
     *   SSECustomerKey?: string,
     *   SSECustomerKeyMD5?: string,
     *   SSEKMSKeyId?: string,
     *   SSEKMSEncryptionContext?: string,
     *   RequestPayer?: \AsyncAws\S3\Enum\RequestPayer::*,
     *   Tagging?: string,
     *   ObjectLockMode?: \AsyncAws\S3\Enum\ObjectLockMode::*,
     *   ObjectLockRetainUntilDate?: \DateTimeInterface|string,
     *   ObjectLockLegalHoldStatus?: \AsyncAws\S3\Enum\ObjectLockLegalHoldStatus::*,
     * }|PutObjectRequest $input
     */
    public function putObject($input): PutObjectOutput
    {
        $input = PutObjectRequest::create($input);
        $input->validate();

        $response = $this->getResponse($input->request());

        return new PutObjectOutput($response, $this->httpClient);
    }

    /**
     * Uses the `acl` subresource to set the access control list (ACL) permissions for an object that already exists in a
     * bucket. You must have `WRITE_ACP` permission to set the ACL of an object.
     *
     * @see http://docs.amazonwebservices.com/AmazonS3/latest/API/RESTObjectPUTacl.html
     *
     * @param array{
     *   ACL?: \AsyncAws\S3\Enum\ObjectCannedACL::*,
     *   AccessControlPolicy?: \AsyncAws\S3\Input\AccessControlPolicy|array,
     *   Bucket: string,
     *   ContentMD5?: string,
     *   GrantFullControl?: string,
     *   GrantRead?: string,
     *   GrantReadACP?: string,
     *   GrantWrite?: string,
     *   GrantWriteACP?: string,
     *   Key: string,
     *   RequestPayer?: \AsyncAws\S3\Enum\RequestPayer::*,
     *   VersionId?: string,
     * }|PutObjectAclRequest $input
     */
    public function putObjectAcl($input): PutObjectAclOutput
    {
        $input = PutObjectAclRequest::create($input);
        $input->validate();

        $response = $this->getResponse($input->request());

        return new PutObjectAclOutput($response, $this->httpClient);
    }

    protected function getServiceCode(): string
    {
        return 's3';
    }

    protected function getSignatureScopeName(): string
    {
        return 's3';
    }

    protected function getSignatureVersion(): string
    {
        return 's3';
    }

    /**
     * @return callable[]
     */
    protected function getSignerFactories(): array
    {
        return [
            's3' => static function (string $service, string $region) {
                return new SignerV4ForS3($service, $region);
            },
        ] + parent::getSignerFactories();
    }
}
