<?php

namespace AsyncAws\S3;

use AsyncAws\Core\AbstractApi;
use AsyncAws\Core\XmlBuilder;
use AsyncAws\S3\Input\CopyObjectRequest;
use AsyncAws\S3\Input\CreateBucketRequest;
use AsyncAws\S3\Input\DeleteObjectRequest;
use AsyncAws\S3\Input\GetObjectAclRequest;
use AsyncAws\S3\Input\GetObjectRequest;
use AsyncAws\S3\Input\HeadObjectRequest;
use AsyncAws\S3\Input\ListObjectsRequest;
use AsyncAws\S3\Input\PutObjectAclRequest;
use AsyncAws\S3\Input\PutObjectRequest;
use AsyncAws\S3\Result\CopyObjectOutput;
use AsyncAws\S3\Result\CreateBucketOutput;
use AsyncAws\S3\Result\DeleteObjectOutput;
use AsyncAws\S3\Result\GetObjectAclOutput;
use AsyncAws\S3\Result\GetObjectOutput;
use AsyncAws\S3\Result\HeadObjectOutput;
use AsyncAws\S3\Result\ListObjectsOutput;
use AsyncAws\S3\Result\PutObjectAclOutput;
use AsyncAws\S3\Result\PutObjectOutput;

class S3Client extends AbstractApi
{
    /**
     * Creates a copy of an object that is already stored in Amazon S3.
     *
     * @see http://docs.amazonwebservices.com/AmazonS3/latest/API/RESTObjectCOPY.html
     *
     * @param array{
     *   ACL?: string,
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
     *   Metadata?: array,
     *   MetadataDirective?: string,
     *   TaggingDirective?: string,
     *   ServerSideEncryption?: string,
     *   StorageClass?: string,
     *   WebsiteRedirectLocation?: string,
     *   SSECustomerAlgorithm?: string,
     *   SSECustomerKey?: string,
     *   SSECustomerKeyMD5?: string,
     *   SSEKMSKeyId?: string,
     *   SSEKMSEncryptionContext?: string,
     *   CopySourceSSECustomerAlgorithm?: string,
     *   CopySourceSSECustomerKey?: string,
     *   CopySourceSSECustomerKeyMD5?: string,
     *   RequestPayer?: string,
     *   Tagging?: string,
     *   ObjectLockMode?: string,
     *   ObjectLockRetainUntilDate?: \DateTimeImmutable|string,
     *   ObjectLockLegalHoldStatus?: string,
     * }|CopyObjectRequest $input
     */
    public function copyObject($input): CopyObjectOutput
    {
        $input = CopyObjectRequest::create($input);
        $input->validate();

        $response = $this->getResponse(
            'PUT',
            $input->requestBody(),
            $input->requestHeaders(),
            $this->getEndpoint($input->requestUri(), $input->requestQuery())
        );

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
     *   ACL?: string,
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
        $xmlConfig = ['CreateBucketConfiguration' => ['type' => 'structure', 'members' => ['LocationConstraint' => ['shape' => 'BucketLocationConstraint']]], 'BucketLocationConstraint' => ['type' => 'string'], '_root' => ['type' => 'CreateBucketConfiguration', 'xmlName' => 'CreateBucketConfiguration', 'uri' => 'http://s3.amazonaws.com/doc/2006-03-01/']];
        $payload = (new XmlBuilder($input->requestBody(), $xmlConfig))->getXml();

        $response = $this->getResponse(
            'PUT',
            $payload,
            $input->requestHeaders(),
            $this->getEndpoint($input->requestUri(), $input->requestQuery())
        );

        return new CreateBucketOutput($response);
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
     *   RequestPayer?: string,
     *   BypassGovernanceRetention?: bool,
     * }|DeleteObjectRequest $input
     */
    public function deleteObject($input): DeleteObjectOutput
    {
        $input = DeleteObjectRequest::create($input);
        $input->validate();

        $response = $this->getResponse(
            'DELETE',
            $input->requestBody(),
            $input->requestHeaders(),
            $this->getEndpoint($input->requestUri(), $input->requestQuery())
        );

        return new DeleteObjectOutput($response);
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
     *   RequestPayer?: string,
     *   PartNumber?: int,
     * }|GetObjectRequest $input
     */
    public function getObject($input): GetObjectOutput
    {
        $input = GetObjectRequest::create($input);
        $input->validate();

        $response = $this->getResponse(
            'GET',
            $input->requestBody(),
            $input->requestHeaders(),
            $this->getEndpoint($input->requestUri(), $input->requestQuery())
        );

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
     *   RequestPayer?: string,
     * }|GetObjectAclRequest $input
     */
    public function getObjectAcl($input): GetObjectAclOutput
    {
        $input = GetObjectAclRequest::create($input);
        $input->validate();

        $response = $this->getResponse(
            'GET',
            $input->requestBody(),
            $input->requestHeaders(),
            $this->getEndpoint($input->requestUri(), $input->requestQuery())
        );

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
     *   RequestPayer?: string,
     *   PartNumber?: int,
     * }|HeadObjectRequest $input
     */
    public function headObject($input): HeadObjectOutput
    {
        $input = HeadObjectRequest::create($input);
        $input->validate();

        $response = $this->getResponse(
            'HEAD',
            $input->requestBody(),
            $input->requestHeaders(),
            $this->getEndpoint($input->requestUri(), $input->requestQuery())
        );

        return new HeadObjectOutput($response);
    }

    /**
     * Returns some or all (up to 1,000) of the objects in a bucket. You can use the request parameters as selection
     * criteria to return a subset of the objects in a bucket. A 200 OK response can contain valid or invalid XML. Be sure
     * to design your application to parse the contents of the response and handle it appropriately.
     *
     * @see http://docs.amazonwebservices.com/AmazonS3/latest/API/RESTBucketGET.html
     *
     * @param array{
     *   Bucket: string,
     *   Delimiter?: string,
     *   EncodingType?: string,
     *   Marker?: string,
     *   MaxKeys?: int,
     *   Prefix?: string,
     *   RequestPayer?: string,
     * }|ListObjectsRequest $input
     */
    public function listObjects($input): ListObjectsOutput
    {
        $input = ListObjectsRequest::create($input);
        $input->validate();

        $response = $this->getResponse(
            'GET',
            $input->requestBody(),
            $input->requestHeaders(),
            $this->getEndpoint($input->requestUri(), $input->requestQuery())
        );

        return new ListObjectsOutput($response);
    }

    /**
     * Adds an object to a bucket. You must have WRITE permissions on a bucket to add an object to it.
     *
     * @see http://docs.amazonwebservices.com/AmazonS3/latest/API/RESTObjectPUT.html
     *
     * @param array{
     *   ACL?: string,
     *   Body?: string|resource|\Closure,
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
     *   Metadata?: array,
     *   ServerSideEncryption?: string,
     *   StorageClass?: string,
     *   WebsiteRedirectLocation?: string,
     *   SSECustomerAlgorithm?: string,
     *   SSECustomerKey?: string,
     *   SSECustomerKeyMD5?: string,
     *   SSEKMSKeyId?: string,
     *   SSEKMSEncryptionContext?: string,
     *   RequestPayer?: string,
     *   Tagging?: string,
     *   ObjectLockMode?: string,
     *   ObjectLockRetainUntilDate?: \DateTimeImmutable|string,
     *   ObjectLockLegalHoldStatus?: string,
     * }|PutObjectRequest $input
     */
    public function putObject($input): PutObjectOutput
    {
        $input = PutObjectRequest::create($input);
        $input->validate();
        $payload = $input->getBody() ?? '';
        $response = $this->getResponse(
            'PUT',
            $payload,
            $input->requestHeaders(),
            $this->getEndpoint($input->requestUri(), $input->requestQuery())
        );

        return new PutObjectOutput($response);
    }

    /**
     * Uses the `acl` subresource to set the access control list (ACL) permissions for an object that already exists in a
     * bucket. You must have `WRITE_ACP` permission to set the ACL of an object.
     *
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
     * }|PutObjectAclRequest $input
     */
    public function putObjectAcl($input): PutObjectAclOutput
    {
        $input = PutObjectAclRequest::create($input);
        $input->validate();
        $xmlConfig = ['AccessControlPolicy' => ['type' => 'structure', 'members' => ['Grants' => ['shape' => 'Grants', 'locationName' => 'AccessControlList'], 'Owner' => ['shape' => 'Owner']]], 'Grants' => ['type' => 'list', 'member' => ['shape' => 'Grant', 'locationName' => 'Grant']], 'Grant' => ['type' => 'structure', 'members' => ['Grantee' => ['shape' => 'Grantee'], 'Permission' => ['shape' => 'Permission']]], 'Grantee' => ['type' => 'structure', 'required' => [0 => 'Type'], 'members' => ['DisplayName' => ['shape' => 'DisplayName'], 'EmailAddress' => ['shape' => 'EmailAddress'], 'ID' => ['shape' => 'ID'], 'Type' => ['shape' => 'Type', 'locationName' => 'xsi:type', 'xmlAttribute' => '1'], 'URI' => ['shape' => 'URI']], 'xmlNamespace' => ['prefix' => 'xsi', 'uri' => 'http://www.w3.org/2001/XMLSchema-instance']], 'DisplayName' => ['type' => 'string'], 'EmailAddress' => ['type' => 'string'], 'ID' => ['type' => 'string'], 'Type' => ['type' => 'string'], 'URI' => ['type' => 'string'], 'Permission' => ['type' => 'string'], 'Owner' => ['type' => 'structure', 'members' => ['DisplayName' => ['shape' => 'DisplayName'], 'ID' => ['shape' => 'ID']]], '_root' => ['type' => 'AccessControlPolicy', 'xmlName' => 'AccessControlPolicy', 'uri' => 'http://s3.amazonaws.com/doc/2006-03-01/']];
        $payload = (new XmlBuilder($input->requestBody(), $xmlConfig))->getXml();

        $response = $this->getResponse(
            'PUT',
            $payload,
            $input->requestHeaders(),
            $this->getEndpoint($input->requestUri(), $input->requestQuery())
        );

        return new PutObjectAclOutput($response);
    }

    protected function getServiceCode(): string
    {
        return 's3';
    }

    protected function getSignatureVersion(): string
    {
        return 's3';
    }
}
