<?php

namespace AsyncAws\S3;

use AsyncAws\Core\AbstractApi;
use AsyncAws\Core\RequestContext;
use AsyncAws\S3\Input\AbortMultipartUploadRequest;
use AsyncAws\S3\Input\CompleteMultipartUploadRequest;
use AsyncAws\S3\Input\CopyObjectRequest;
use AsyncAws\S3\Input\CreateBucketRequest;
use AsyncAws\S3\Input\CreateMultipartUploadRequest;
use AsyncAws\S3\Input\DeleteObjectRequest;
use AsyncAws\S3\Input\DeleteObjectsRequest;
use AsyncAws\S3\Input\GetObjectAclRequest;
use AsyncAws\S3\Input\GetObjectRequest;
use AsyncAws\S3\Input\HeadBucketRequest;
use AsyncAws\S3\Input\HeadObjectRequest;
use AsyncAws\S3\Input\ListMultipartUploadsRequest;
use AsyncAws\S3\Input\ListObjectsV2Request;
use AsyncAws\S3\Input\PutObjectAclRequest;
use AsyncAws\S3\Input\PutObjectRequest;
use AsyncAws\S3\Result\AbortMultipartUploadOutput;
use AsyncAws\S3\Result\BucketExistsWaiter;
use AsyncAws\S3\Result\BucketNotExistsWaiter;
use AsyncAws\S3\Result\CompleteMultipartUploadOutput;
use AsyncAws\S3\Result\CopyObjectOutput;
use AsyncAws\S3\Result\CreateBucketOutput;
use AsyncAws\S3\Result\CreateMultipartUploadOutput;
use AsyncAws\S3\Result\DeleteObjectOutput;
use AsyncAws\S3\Result\DeleteObjectsOutput;
use AsyncAws\S3\Result\GetObjectAclOutput;
use AsyncAws\S3\Result\GetObjectOutput;
use AsyncAws\S3\Result\HeadObjectOutput;
use AsyncAws\S3\Result\ListMultipartUploadsOutput;
use AsyncAws\S3\Result\ListObjectsV2Output;
use AsyncAws\S3\Result\ObjectExistsWaiter;
use AsyncAws\S3\Result\ObjectNotExistsWaiter;
use AsyncAws\S3\Result\PutObjectAclOutput;
use AsyncAws\S3\Result\PutObjectOutput;
use AsyncAws\S3\Signer\SignerV4ForS3;

class S3Client extends AbstractApi
{
    /**
     * This operation aborts a multipart upload. After a multipart upload is aborted, no additional parts can be uploaded
     * using that upload ID. The storage consumed by any previously uploaded parts will be freed. However, if any part
     * uploads are currently in progress, those part uploads might or might not succeed. As a result, it might be necessary
     * to abort a given multipart upload multiple times in order to completely free all storage consumed by all parts.
     *
     * @see http://docs.amazonwebservices.com/AmazonS3/latest/API/mpUploadAbort.html
     *
     * @param array{
     *   Bucket: string,
     *   Key: string,
     *   UploadId: string,
     *   RequestPayer?: \AsyncAws\S3\Enum\RequestPayer::*,
     * }|AbortMultipartUploadRequest $input
     */
    public function abortMultipartUpload($input): AbortMultipartUploadOutput
    {
        $response = $this->getResponse(AbortMultipartUploadRequest::create($input)->request(), new RequestContext(['operation' => 'AbortMultipartUpload']));

        return new AbortMultipartUploadOutput($response);
    }

    /**
     * Check status of operation headBucket.
     *
     * @see headBucket
     *
     * @param array{
     *   Bucket: string,
     * }|HeadBucketRequest $input
     */
    public function bucketExists($input): BucketExistsWaiter
    {
        $input = HeadBucketRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'HeadBucket']));

        return new BucketExistsWaiter($response, $this, $input);
    }

    /**
     * Check status of operation headBucket.
     *
     * @see headBucket
     *
     * @param array{
     *   Bucket: string,
     * }|HeadBucketRequest $input
     */
    public function bucketNotExists($input): BucketNotExistsWaiter
    {
        $input = HeadBucketRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'HeadBucket']));

        return new BucketNotExistsWaiter($response, $this, $input);
    }

    /**
     * Completes a multipart upload by assembling previously uploaded parts.
     *
     * @see http://docs.amazonwebservices.com/AmazonS3/latest/API/mpUploadComplete.html
     *
     * @param array{
     *   Bucket: string,
     *   Key: string,
     *   MultipartUpload?: \AsyncAws\S3\ValueObject\CompletedMultipartUpload|array,
     *   UploadId: string,
     *   RequestPayer?: \AsyncAws\S3\Enum\RequestPayer::*,
     * }|CompleteMultipartUploadRequest $input
     */
    public function completeMultipartUpload($input): CompleteMultipartUploadOutput
    {
        $response = $this->getResponse(CompleteMultipartUploadRequest::create($input)->request(), new RequestContext(['operation' => 'CompleteMultipartUpload']));

        return new CompleteMultipartUploadOutput($response);
    }

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
     *   CopySourceIfModifiedSince?: \DateTimeImmutable|string,
     *   CopySourceIfNoneMatch?: string,
     *   CopySourceIfUnmodifiedSince?: \DateTimeImmutable|string,
     *   Expires?: \DateTimeImmutable|string,
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
     *   ObjectLockRetainUntilDate?: \DateTimeImmutable|string,
     *   ObjectLockLegalHoldStatus?: \AsyncAws\S3\Enum\ObjectLockLegalHoldStatus::*,
     * }|CopyObjectRequest $input
     */
    public function copyObject($input): CopyObjectOutput
    {
        $response = $this->getResponse(CopyObjectRequest::create($input)->request(), new RequestContext(['operation' => 'CopyObject']));

        return new CopyObjectOutput($response);
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
     *   CreateBucketConfiguration?: \AsyncAws\S3\ValueObject\CreateBucketConfiguration|array,
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
        $response = $this->getResponse(CreateBucketRequest::create($input)->request(), new RequestContext(['operation' => 'CreateBucket']));

        return new CreateBucketOutput($response);
    }

    /**
     * This operation initiates a multipart upload and returns an upload ID. This upload ID is used to associate all of the
     * parts in the specific multipart upload. You specify this upload ID in each of your subsequent upload part requests
     * (see UploadPart). You also include this upload ID in the final request to either complete or abort the multipart
     * upload request.
     *
     * @see http://docs.amazonwebservices.com/AmazonS3/latest/API/mpUploadInitiate.html
     *
     * @param array{
     *   ACL?: \AsyncAws\S3\Enum\ObjectCannedACL::*,
     *   Bucket: string,
     *   CacheControl?: string,
     *   ContentDisposition?: string,
     *   ContentEncoding?: string,
     *   ContentLanguage?: string,
     *   ContentType?: string,
     *   Expires?: \DateTimeImmutable|string,
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
     *   ObjectLockRetainUntilDate?: \DateTimeImmutable|string,
     *   ObjectLockLegalHoldStatus?: \AsyncAws\S3\Enum\ObjectLockLegalHoldStatus::*,
     * }|CreateMultipartUploadRequest $input
     */
    public function createMultipartUpload($input): CreateMultipartUploadOutput
    {
        $response = $this->getResponse(CreateMultipartUploadRequest::create($input)->request(), new RequestContext(['operation' => 'CreateMultipartUpload']));

        return new CreateMultipartUploadOutput($response);
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
        $response = $this->getResponse(DeleteObjectRequest::create($input)->request(), new RequestContext(['operation' => 'DeleteObject']));

        return new DeleteObjectOutput($response);
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
     *   Delete: \AsyncAws\S3\ValueObject\Delete|array,
     *   MFA?: string,
     *   RequestPayer?: \AsyncAws\S3\Enum\RequestPayer::*,
     *   BypassGovernanceRetention?: bool,
     * }|DeleteObjectsRequest $input
     */
    public function deleteObjects($input): DeleteObjectsOutput
    {
        $response = $this->getResponse(DeleteObjectsRequest::create($input)->request(), new RequestContext(['operation' => 'DeleteObjects']));

        return new DeleteObjectsOutput($response);
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
     *   IfModifiedSince?: \DateTimeImmutable|string,
     *   IfNoneMatch?: string,
     *   IfUnmodifiedSince?: \DateTimeImmutable|string,
     *   Key: string,
     *   Range?: string,
     *   ResponseCacheControl?: string,
     *   ResponseContentDisposition?: string,
     *   ResponseContentEncoding?: string,
     *   ResponseContentLanguage?: string,
     *   ResponseContentType?: string,
     *   ResponseExpires?: \DateTimeImmutable|string,
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
        $response = $this->getResponse(GetObjectRequest::create($input)->request(), new RequestContext(['operation' => 'GetObject']));

        return new GetObjectOutput($response);
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
        $response = $this->getResponse(GetObjectAclRequest::create($input)->request(), new RequestContext(['operation' => 'GetObjectAcl']));

        return new GetObjectAclOutput($response);
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
     *   IfModifiedSince?: \DateTimeImmutable|string,
     *   IfNoneMatch?: string,
     *   IfUnmodifiedSince?: \DateTimeImmutable|string,
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
        $response = $this->getResponse(HeadObjectRequest::create($input)->request(), new RequestContext(['operation' => 'HeadObject']));

        return new HeadObjectOutput($response);
    }

    /**
     * This operation lists in-progress multipart uploads. An in-progress multipart upload is a multipart upload that has
     * been initiated using the Initiate Multipart Upload request, but has not yet been completed or aborted.
     *
     * @see http://docs.amazonwebservices.com/AmazonS3/latest/API/mpUploadListMPUpload.html
     *
     * @param array{
     *   Bucket: string,
     *   Delimiter?: string,
     *   EncodingType?: \AsyncAws\S3\Enum\EncodingType::*,
     *   KeyMarker?: string,
     *   MaxUploads?: int,
     *   Prefix?: string,
     *   UploadIdMarker?: string,
     * }|ListMultipartUploadsRequest $input
     */
    public function listMultipartUploads($input): ListMultipartUploadsOutput
    {
        $input = ListMultipartUploadsRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'ListMultipartUploads']));

        return new ListMultipartUploadsOutput($response, $this, $input);
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
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'ListObjectsV2']));

        return new ListObjectsV2Output($response, $this, $input);
    }

    /**
     * Check status of operation headObject.
     *
     * @see headObject
     *
     * @param array{
     *   Bucket: string,
     *   IfMatch?: string,
     *   IfModifiedSince?: \DateTimeImmutable|string,
     *   IfNoneMatch?: string,
     *   IfUnmodifiedSince?: \DateTimeImmutable|string,
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
    public function objectExists($input): ObjectExistsWaiter
    {
        $input = HeadObjectRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'HeadObject']));

        return new ObjectExistsWaiter($response, $this, $input);
    }

    /**
     * Check status of operation headObject.
     *
     * @see headObject
     *
     * @param array{
     *   Bucket: string,
     *   IfMatch?: string,
     *   IfModifiedSince?: \DateTimeImmutable|string,
     *   IfNoneMatch?: string,
     *   IfUnmodifiedSince?: \DateTimeImmutable|string,
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
    public function objectNotExists($input): ObjectNotExistsWaiter
    {
        $input = HeadObjectRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'HeadObject']));

        return new ObjectNotExistsWaiter($response, $this, $input);
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
     *   Expires?: \DateTimeImmutable|string,
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
     *   ObjectLockRetainUntilDate?: \DateTimeImmutable|string,
     *   ObjectLockLegalHoldStatus?: \AsyncAws\S3\Enum\ObjectLockLegalHoldStatus::*,
     * }|PutObjectRequest $input
     */
    public function putObject($input): PutObjectOutput
    {
        $response = $this->getResponse(PutObjectRequest::create($input)->request(), new RequestContext(['operation' => 'PutObject']));

        return new PutObjectOutput($response);
    }

    /**
     * Uses the `acl` subresource to set the access control list (ACL) permissions for an object that already exists in a
     * bucket. You must have `WRITE_ACP` permission to set the ACL of an object.
     *
     * @see http://docs.amazonwebservices.com/AmazonS3/latest/API/RESTObjectPUTacl.html
     *
     * @param array{
     *   ACL?: \AsyncAws\S3\Enum\ObjectCannedACL::*,
     *   AccessControlPolicy?: \AsyncAws\S3\ValueObject\AccessControlPolicy|array,
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
        $response = $this->getResponse(PutObjectAclRequest::create($input)->request(), new RequestContext(['operation' => 'PutObjectAcl']));

        return new PutObjectAclOutput($response);
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
