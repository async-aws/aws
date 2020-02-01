<?php

namespace AsyncAws\S3;

use AsyncAws\Core\AbstractApi;
use AsyncAws\Core\XmlBuilder;
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
    protected function getServiceCode(): string
    {
        return 's3';
    }

    protected function getSignatureVersion(): string
    {
        return 's3';
    }

    protected function getEndpoint(array $uri, array $query): ?string
    {
        return \sprintf('https://%s.s3.%%region%%.amazonaws.com/%s', $uri['Bucket'], ltrim($uri['Key'], '/'));
    }

    /**
     * @see http://docs.amazonwebservices.com/AmazonS3/latest/API/RESTObjectPUT.html
     *
     * @param array{
     *   ACL?: string
     *   Body?: string
     *   Bucket: string
     *   CacheControl?: string
     *   ContentDisposition?: string
     *   ContentEncoding?: string
     *   ContentLanguage?: string
     *   ContentLength?: string
     *   ContentMD5?: string
     *   ContentType?: string
     *   Expires?: int
     *   GrantFullControl?: string
     *   GrantRead?: string
     *   GrantReadACP?: string
     *   GrantWriteACP?: string
     *   Key: string
     *   Metadata?: array
     *   ServerSideEncryption?: string
     *   StorageClass?: string
     *   WebsiteRedirectLocation?: string
     *   SSECustomerAlgorithm?: string
     *   SSECustomerKey?: string
     *   SSECustomerKeyMD5?: string
     *   SSEKMSKeyId?: string
     *   SSEKMSEncryptionContext?: string
     *   RequestPayer?: string
     *   Tagging?: string
     *   ObjectLockMode?: string
     *   ObjectLockRetainUntilDate?: int
     *   ObjectLockLegalHoldStatus?: string
     * } $input
     */
    public function putObject(array $input): PutObjectOutput
    {
        $uri = [];
        $query = [];
        $headers = [];
        if (\array_key_exists("ACL", $input)) {
            $headers["x-amz-acl"] = $input["ACL"];
        }
        if (\array_key_exists("CacheControl", $input)) {
            $headers["Cache-Control"] = $input["CacheControl"];
        }
        if (\array_key_exists("ContentDisposition", $input)) {
            $headers["Content-Disposition"] = $input["ContentDisposition"];
        }
        if (\array_key_exists("ContentEncoding", $input)) {
            $headers["Content-Encoding"] = $input["ContentEncoding"];
        }
        if (\array_key_exists("ContentLanguage", $input)) {
            $headers["Content-Language"] = $input["ContentLanguage"];
        }
        if (\array_key_exists("ContentLength", $input)) {
            $headers["Content-Length"] = $input["ContentLength"];
        }
        if (\array_key_exists("ContentMD5", $input)) {
            $headers["Content-MD5"] = $input["ContentMD5"];
        }
        if (\array_key_exists("ContentType", $input)) {
            $headers["Content-Type"] = $input["ContentType"];
        }
        if (\array_key_exists("Expires", $input)) {
            $headers["Expires"] = $input["Expires"];
        }
        if (\array_key_exists("GrantFullControl", $input)) {
            $headers["x-amz-grant-full-control"] = $input["GrantFullControl"];
        }
        if (\array_key_exists("GrantRead", $input)) {
            $headers["x-amz-grant-read"] = $input["GrantRead"];
        }
        if (\array_key_exists("GrantReadACP", $input)) {
            $headers["x-amz-grant-read-acp"] = $input["GrantReadACP"];
        }
        if (\array_key_exists("GrantWriteACP", $input)) {
            $headers["x-amz-grant-write-acp"] = $input["GrantWriteACP"];
        }
        if (\array_key_exists("ServerSideEncryption", $input)) {
            $headers["x-amz-server-side-encryption"] = $input["ServerSideEncryption"];
        }
        if (\array_key_exists("StorageClass", $input)) {
            $headers["x-amz-storage-class"] = $input["StorageClass"];
        }
        if (\array_key_exists("WebsiteRedirectLocation", $input)) {
            $headers["x-amz-website-redirect-location"] = $input["WebsiteRedirectLocation"];
        }
        if (\array_key_exists("SSECustomerAlgorithm", $input)) {
            $headers["x-amz-server-side-encryption-customer-algorithm"] = $input["SSECustomerAlgorithm"];
        }
        if (\array_key_exists("SSECustomerKey", $input)) {
            $headers["x-amz-server-side-encryption-customer-key"] = $input["SSECustomerKey"];
        }
        if (\array_key_exists("SSECustomerKeyMD5", $input)) {
            $headers["x-amz-server-side-encryption-customer-key-MD5"] = $input["SSECustomerKeyMD5"];
        }
        if (\array_key_exists("SSEKMSKeyId", $input)) {
            $headers["x-amz-server-side-encryption-aws-kms-key-id"] = $input["SSEKMSKeyId"];
        }
        if (\array_key_exists("SSEKMSEncryptionContext", $input)) {
            $headers["x-amz-server-side-encryption-context"] = $input["SSEKMSEncryptionContext"];
        }
        if (\array_key_exists("RequestPayer", $input)) {
            $headers["x-amz-request-payer"] = $input["RequestPayer"];
        }
        if (\array_key_exists("Tagging", $input)) {
            $headers["x-amz-tagging"] = $input["Tagging"];
        }
        if (\array_key_exists("ObjectLockMode", $input)) {
            $headers["x-amz-object-lock-mode"] = $input["ObjectLockMode"];
        }
        if (\array_key_exists("ObjectLockRetainUntilDate", $input)) {
            $headers["x-amz-object-lock-retain-until-date"] = $input["ObjectLockRetainUntilDate"];
        }
        if (\array_key_exists("ObjectLockLegalHoldStatus", $input)) {
            $headers["x-amz-object-lock-legal-hold"] = $input["ObjectLockLegalHoldStatus"];
        }
        if (\array_key_exists("Bucket", $input)) {
            $uri["Bucket"] = $input["Bucket"];
        }
        if (\array_key_exists("Key", $input)) {
            $uri["Key"] = $input["Key"];
        }
        $payload = $input["Body"];
        $response = $this->getResponse('PUT', $payload, $headers, $this->getEndpoint($uri, $query));

        return new PutObjectOutput($response);
    }

    /**
     * @see http://docs.amazonwebservices.com/AmazonS3/latest/API/RESTBucketPUT.html
     *
     * @param array{
     *   ACL?: string
     *   Bucket: string
     *   CreateBucketConfiguration?: array
     *   GrantFullControl?: string
     *   GrantRead?: string
     *   GrantReadACP?: string
     *   GrantWrite?: string
     *   GrantWriteACP?: string
     *   ObjectLockEnabledForBucket?: bool
     * } $input
     */
    public function createBucket(array $input): CreateBucketOutput
    {
        $uri = [];
        $query = [];
        $headers = [];
        if (\array_key_exists("ACL", $input)) {
            $headers["x-amz-acl"] = $input["ACL"];
        }
        if (\array_key_exists("GrantFullControl", $input)) {
            $headers["x-amz-grant-full-control"] = $input["GrantFullControl"];
        }
        if (\array_key_exists("GrantRead", $input)) {
            $headers["x-amz-grant-read"] = $input["GrantRead"];
        }
        if (\array_key_exists("GrantReadACP", $input)) {
            $headers["x-amz-grant-read-acp"] = $input["GrantReadACP"];
        }
        if (\array_key_exists("GrantWrite", $input)) {
            $headers["x-amz-grant-write"] = $input["GrantWrite"];
        }
        if (\array_key_exists("GrantWriteACP", $input)) {
            $headers["x-amz-grant-write-acp"] = $input["GrantWriteACP"];
        }
        if (\array_key_exists("ObjectLockEnabledForBucket", $input)) {
            $headers["x-amz-bucket-object-lock-enabled"] = $input["ObjectLockEnabledForBucket"];
        }
        if (\array_key_exists("Bucket", $input)) {
            $uri["Bucket"] = $input["Bucket"];
        }
        $xmlConfig = ["CreateBucketConfiguration" => ["type" => 'structure',"members" => ["LocationConstraint" => ["shape" => 'BucketLocationConstraint']]],"BucketLocationConstraint" => ["type" => 'string'],"_root" => ["type" => 'CreateBucketConfiguration',"xmlName" => 'CreateBucketConfiguration',"uri" => 'http://s3.amazonaws.com/doc/2006-03-01/']];
        $payload = (new XmlBuilder($input["CreateBucketConfiguration"], $xmlConfig))->getXml();
        $response = $this->getResponse('PUT', $payload, $headers, $this->getEndpoint($uri, $query));

        return new CreateBucketOutput($response);
    }

    /**
     * @see http://docs.amazonwebservices.com/AmazonS3/latest/API/RESTObjectDELETE.html
     *
     * @param array{
     *   Bucket: string
     *   Key: string
     *   MFA?: string
     *   VersionId?: string
     *   RequestPayer?: string
     *   BypassGovernanceRetention?: bool
     * } $input
     */
    public function deleteObject(array $input): DeleteObjectOutput
    {
        $uri = [];
        $query = [];
        $headers = [];
        if (\array_key_exists("MFA", $input)) {
            $headers["x-amz-mfa"] = $input["MFA"];
        }
        if (\array_key_exists("RequestPayer", $input)) {
            $headers["x-amz-request-payer"] = $input["RequestPayer"];
        }
        if (\array_key_exists("BypassGovernanceRetention", $input)) {
            $headers["x-amz-bypass-governance-retention"] = $input["BypassGovernanceRetention"];
        }
        if (\array_key_exists("VersionId", $input)) {
            $query["versionId"] = $input["VersionId"];
        }
        if (\array_key_exists("Bucket", $input)) {
            $uri["Bucket"] = $input["Bucket"];
        }
        if (\array_key_exists("Key", $input)) {
            $uri["Key"] = $input["Key"];
        }
        $payload = "";
        $response = $this->getResponse('DELETE', $payload, $headers, $this->getEndpoint($uri, $query));

        return new DeleteObjectOutput($response);
    }

    /**
     * @see http://docs.amazonwebservices.com/AmazonS3/latest/API/RESTObjectHEAD.html
     *
     * @param array{
     *   Bucket: string
     *   IfMatch?: string
     *   IfModifiedSince?: int
     *   IfNoneMatch?: string
     *   IfUnmodifiedSince?: int
     *   Key: string
     *   Range?: string
     *   VersionId?: string
     *   SSECustomerAlgorithm?: string
     *   SSECustomerKey?: string
     *   SSECustomerKeyMD5?: string
     *   RequestPayer?: string
     *   PartNumber?: int
     * } $input
     */
    public function headObject(array $input): HeadObjectOutput
    {
        $uri = [];
        $query = [];
        $headers = [];
        if (\array_key_exists("IfMatch", $input)) {
            $headers["If-Match"] = $input["IfMatch"];
        }
        if (\array_key_exists("IfModifiedSince", $input)) {
            $headers["If-Modified-Since"] = $input["IfModifiedSince"];
        }
        if (\array_key_exists("IfNoneMatch", $input)) {
            $headers["If-None-Match"] = $input["IfNoneMatch"];
        }
        if (\array_key_exists("IfUnmodifiedSince", $input)) {
            $headers["If-Unmodified-Since"] = $input["IfUnmodifiedSince"];
        }
        if (\array_key_exists("Range", $input)) {
            $headers["Range"] = $input["Range"];
        }
        if (\array_key_exists("SSECustomerAlgorithm", $input)) {
            $headers["x-amz-server-side-encryption-customer-algorithm"] = $input["SSECustomerAlgorithm"];
        }
        if (\array_key_exists("SSECustomerKey", $input)) {
            $headers["x-amz-server-side-encryption-customer-key"] = $input["SSECustomerKey"];
        }
        if (\array_key_exists("SSECustomerKeyMD5", $input)) {
            $headers["x-amz-server-side-encryption-customer-key-MD5"] = $input["SSECustomerKeyMD5"];
        }
        if (\array_key_exists("RequestPayer", $input)) {
            $headers["x-amz-request-payer"] = $input["RequestPayer"];
        }
        if (\array_key_exists("VersionId", $input)) {
            $query["versionId"] = $input["VersionId"];
        }
        if (\array_key_exists("PartNumber", $input)) {
            $query["partNumber"] = $input["PartNumber"];
        }
        if (\array_key_exists("Bucket", $input)) {
            $uri["Bucket"] = $input["Bucket"];
        }
        if (\array_key_exists("Key", $input)) {
            $uri["Key"] = $input["Key"];
        }
        $payload = "";
        $response = $this->getResponse('HEAD', $payload, $headers, $this->getEndpoint($uri, $query));

        return new HeadObjectOutput($response);
    }

    /**
     * @see http://docs.amazonwebservices.com/AmazonS3/latest/API/RESTObjectCOPY.html
     *
     * @param array{
     *   ACL?: string
     *   Bucket: string
     *   CacheControl?: string
     *   ContentDisposition?: string
     *   ContentEncoding?: string
     *   ContentLanguage?: string
     *   ContentType?: string
     *   CopySource: string
     *   CopySourceIfMatch?: string
     *   CopySourceIfModifiedSince?: int
     *   CopySourceIfNoneMatch?: string
     *   CopySourceIfUnmodifiedSince?: int
     *   Expires?: int
     *   GrantFullControl?: string
     *   GrantRead?: string
     *   GrantReadACP?: string
     *   GrantWriteACP?: string
     *   Key: string
     *   Metadata?: array
     *   MetadataDirective?: string
     *   TaggingDirective?: string
     *   ServerSideEncryption?: string
     *   StorageClass?: string
     *   WebsiteRedirectLocation?: string
     *   SSECustomerAlgorithm?: string
     *   SSECustomerKey?: string
     *   SSECustomerKeyMD5?: string
     *   SSEKMSKeyId?: string
     *   SSEKMSEncryptionContext?: string
     *   CopySourceSSECustomerAlgorithm?: string
     *   CopySourceSSECustomerKey?: string
     *   CopySourceSSECustomerKeyMD5?: string
     *   RequestPayer?: string
     *   Tagging?: string
     *   ObjectLockMode?: string
     *   ObjectLockRetainUntilDate?: int
     *   ObjectLockLegalHoldStatus?: string
     * } $input
     */
    public function copyObject(array $input): CopyObjectOutput
    {
        $uri = [];
        $query = [];
        $headers = [];
        if (\array_key_exists("ACL", $input)) {
            $headers["x-amz-acl"] = $input["ACL"];
        }
        if (\array_key_exists("CacheControl", $input)) {
            $headers["Cache-Control"] = $input["CacheControl"];
        }
        if (\array_key_exists("ContentDisposition", $input)) {
            $headers["Content-Disposition"] = $input["ContentDisposition"];
        }
        if (\array_key_exists("ContentEncoding", $input)) {
            $headers["Content-Encoding"] = $input["ContentEncoding"];
        }
        if (\array_key_exists("ContentLanguage", $input)) {
            $headers["Content-Language"] = $input["ContentLanguage"];
        }
        if (\array_key_exists("ContentType", $input)) {
            $headers["Content-Type"] = $input["ContentType"];
        }
        if (\array_key_exists("CopySource", $input)) {
            $headers["x-amz-copy-source"] = $input["CopySource"];
        }
        if (\array_key_exists("CopySourceIfMatch", $input)) {
            $headers["x-amz-copy-source-if-match"] = $input["CopySourceIfMatch"];
        }
        if (\array_key_exists("CopySourceIfModifiedSince", $input)) {
            $headers["x-amz-copy-source-if-modified-since"] = $input["CopySourceIfModifiedSince"];
        }
        if (\array_key_exists("CopySourceIfNoneMatch", $input)) {
            $headers["x-amz-copy-source-if-none-match"] = $input["CopySourceIfNoneMatch"];
        }
        if (\array_key_exists("CopySourceIfUnmodifiedSince", $input)) {
            $headers["x-amz-copy-source-if-unmodified-since"] = $input["CopySourceIfUnmodifiedSince"];
        }
        if (\array_key_exists("Expires", $input)) {
            $headers["Expires"] = $input["Expires"];
        }
        if (\array_key_exists("GrantFullControl", $input)) {
            $headers["x-amz-grant-full-control"] = $input["GrantFullControl"];
        }
        if (\array_key_exists("GrantRead", $input)) {
            $headers["x-amz-grant-read"] = $input["GrantRead"];
        }
        if (\array_key_exists("GrantReadACP", $input)) {
            $headers["x-amz-grant-read-acp"] = $input["GrantReadACP"];
        }
        if (\array_key_exists("GrantWriteACP", $input)) {
            $headers["x-amz-grant-write-acp"] = $input["GrantWriteACP"];
        }
        if (\array_key_exists("MetadataDirective", $input)) {
            $headers["x-amz-metadata-directive"] = $input["MetadataDirective"];
        }
        if (\array_key_exists("TaggingDirective", $input)) {
            $headers["x-amz-tagging-directive"] = $input["TaggingDirective"];
        }
        if (\array_key_exists("ServerSideEncryption", $input)) {
            $headers["x-amz-server-side-encryption"] = $input["ServerSideEncryption"];
        }
        if (\array_key_exists("StorageClass", $input)) {
            $headers["x-amz-storage-class"] = $input["StorageClass"];
        }
        if (\array_key_exists("WebsiteRedirectLocation", $input)) {
            $headers["x-amz-website-redirect-location"] = $input["WebsiteRedirectLocation"];
        }
        if (\array_key_exists("SSECustomerAlgorithm", $input)) {
            $headers["x-amz-server-side-encryption-customer-algorithm"] = $input["SSECustomerAlgorithm"];
        }
        if (\array_key_exists("SSECustomerKey", $input)) {
            $headers["x-amz-server-side-encryption-customer-key"] = $input["SSECustomerKey"];
        }
        if (\array_key_exists("SSECustomerKeyMD5", $input)) {
            $headers["x-amz-server-side-encryption-customer-key-MD5"] = $input["SSECustomerKeyMD5"];
        }
        if (\array_key_exists("SSEKMSKeyId", $input)) {
            $headers["x-amz-server-side-encryption-aws-kms-key-id"] = $input["SSEKMSKeyId"];
        }
        if (\array_key_exists("SSEKMSEncryptionContext", $input)) {
            $headers["x-amz-server-side-encryption-context"] = $input["SSEKMSEncryptionContext"];
        }
        if (\array_key_exists("CopySourceSSECustomerAlgorithm", $input)) {
            $headers["x-amz-copy-source-server-side-encryption-customer-algorithm"] = $input["CopySourceSSECustomerAlgorithm"];
        }
        if (\array_key_exists("CopySourceSSECustomerKey", $input)) {
            $headers["x-amz-copy-source-server-side-encryption-customer-key"] = $input["CopySourceSSECustomerKey"];
        }
        if (\array_key_exists("CopySourceSSECustomerKeyMD5", $input)) {
            $headers["x-amz-copy-source-server-side-encryption-customer-key-MD5"] = $input["CopySourceSSECustomerKeyMD5"];
        }
        if (\array_key_exists("RequestPayer", $input)) {
            $headers["x-amz-request-payer"] = $input["RequestPayer"];
        }
        if (\array_key_exists("Tagging", $input)) {
            $headers["x-amz-tagging"] = $input["Tagging"];
        }
        if (\array_key_exists("ObjectLockMode", $input)) {
            $headers["x-amz-object-lock-mode"] = $input["ObjectLockMode"];
        }
        if (\array_key_exists("ObjectLockRetainUntilDate", $input)) {
            $headers["x-amz-object-lock-retain-until-date"] = $input["ObjectLockRetainUntilDate"];
        }
        if (\array_key_exists("ObjectLockLegalHoldStatus", $input)) {
            $headers["x-amz-object-lock-legal-hold"] = $input["ObjectLockLegalHoldStatus"];
        }
        if (\array_key_exists("Bucket", $input)) {
            $uri["Bucket"] = $input["Bucket"];
        }
        if (\array_key_exists("Key", $input)) {
            $uri["Key"] = $input["Key"];
        }
        $payload = "";
        $response = $this->getResponse('PUT', $payload, $headers, $this->getEndpoint($uri, $query));

        return new CopyObjectOutput($response);
    }

    /**
     * @see http://docs.amazonwebservices.com/AmazonS3/latest/API/RESTObjectGET.html
     *
     * @param array{
     *   Bucket: string
     *   IfMatch?: string
     *   IfModifiedSince?: int
     *   IfNoneMatch?: string
     *   IfUnmodifiedSince?: int
     *   Key: string
     *   Range?: string
     *   ResponseCacheControl?: string
     *   ResponseContentDisposition?: string
     *   ResponseContentEncoding?: string
     *   ResponseContentLanguage?: string
     *   ResponseContentType?: string
     *   ResponseExpires?: int
     *   VersionId?: string
     *   SSECustomerAlgorithm?: string
     *   SSECustomerKey?: string
     *   SSECustomerKeyMD5?: string
     *   RequestPayer?: string
     *   PartNumber?: int
     * } $input
     */
    public function getObject(array $input): GetObjectOutput
    {
        $uri = [];
        $query = [];
        $headers = [];
        if (\array_key_exists("IfMatch", $input)) {
            $headers["If-Match"] = $input["IfMatch"];
        }
        if (\array_key_exists("IfModifiedSince", $input)) {
            $headers["If-Modified-Since"] = $input["IfModifiedSince"];
        }
        if (\array_key_exists("IfNoneMatch", $input)) {
            $headers["If-None-Match"] = $input["IfNoneMatch"];
        }
        if (\array_key_exists("IfUnmodifiedSince", $input)) {
            $headers["If-Unmodified-Since"] = $input["IfUnmodifiedSince"];
        }
        if (\array_key_exists("Range", $input)) {
            $headers["Range"] = $input["Range"];
        }
        if (\array_key_exists("SSECustomerAlgorithm", $input)) {
            $headers["x-amz-server-side-encryption-customer-algorithm"] = $input["SSECustomerAlgorithm"];
        }
        if (\array_key_exists("SSECustomerKey", $input)) {
            $headers["x-amz-server-side-encryption-customer-key"] = $input["SSECustomerKey"];
        }
        if (\array_key_exists("SSECustomerKeyMD5", $input)) {
            $headers["x-amz-server-side-encryption-customer-key-MD5"] = $input["SSECustomerKeyMD5"];
        }
        if (\array_key_exists("RequestPayer", $input)) {
            $headers["x-amz-request-payer"] = $input["RequestPayer"];
        }
        if (\array_key_exists("ResponseCacheControl", $input)) {
            $query["response-cache-control"] = $input["ResponseCacheControl"];
        }
        if (\array_key_exists("ResponseContentDisposition", $input)) {
            $query["response-content-disposition"] = $input["ResponseContentDisposition"];
        }
        if (\array_key_exists("ResponseContentEncoding", $input)) {
            $query["response-content-encoding"] = $input["ResponseContentEncoding"];
        }
        if (\array_key_exists("ResponseContentLanguage", $input)) {
            $query["response-content-language"] = $input["ResponseContentLanguage"];
        }
        if (\array_key_exists("ResponseContentType", $input)) {
            $query["response-content-type"] = $input["ResponseContentType"];
        }
        if (\array_key_exists("ResponseExpires", $input)) {
            $query["response-expires"] = $input["ResponseExpires"];
        }
        if (\array_key_exists("VersionId", $input)) {
            $query["versionId"] = $input["VersionId"];
        }
        if (\array_key_exists("PartNumber", $input)) {
            $query["partNumber"] = $input["PartNumber"];
        }
        if (\array_key_exists("Bucket", $input)) {
            $uri["Bucket"] = $input["Bucket"];
        }
        if (\array_key_exists("Key", $input)) {
            $uri["Key"] = $input["Key"];
        }
        $payload = "";
        $response = $this->getResponse('GET', $payload, $headers, $this->getEndpoint($uri, $query));

        return new GetObjectOutput($response);
    }

    /**
     * @see http://docs.amazonwebservices.com/AmazonS3/latest/API/RESTObjectPUTacl.html
     *
     * @param array{
     *   ACL?: string
     *   AccessControlPolicy?: array
     *   Bucket: string
     *   ContentMD5?: string
     *   GrantFullControl?: string
     *   GrantRead?: string
     *   GrantReadACP?: string
     *   GrantWrite?: string
     *   GrantWriteACP?: string
     *   Key: string
     *   RequestPayer?: string
     *   VersionId?: string
     * } $input
     */
    public function putObjectAcl(array $input): PutObjectAclOutput
    {
        $uri = [];
        $query = [];
        $headers = [];
        if (\array_key_exists("ACL", $input)) {
            $headers["x-amz-acl"] = $input["ACL"];
        }
        if (\array_key_exists("ContentMD5", $input)) {
            $headers["Content-MD5"] = $input["ContentMD5"];
        }
        if (\array_key_exists("GrantFullControl", $input)) {
            $headers["x-amz-grant-full-control"] = $input["GrantFullControl"];
        }
        if (\array_key_exists("GrantRead", $input)) {
            $headers["x-amz-grant-read"] = $input["GrantRead"];
        }
        if (\array_key_exists("GrantReadACP", $input)) {
            $headers["x-amz-grant-read-acp"] = $input["GrantReadACP"];
        }
        if (\array_key_exists("GrantWrite", $input)) {
            $headers["x-amz-grant-write"] = $input["GrantWrite"];
        }
        if (\array_key_exists("GrantWriteACP", $input)) {
            $headers["x-amz-grant-write-acp"] = $input["GrantWriteACP"];
        }
        if (\array_key_exists("RequestPayer", $input)) {
            $headers["x-amz-request-payer"] = $input["RequestPayer"];
        }
        if (\array_key_exists("VersionId", $input)) {
            $query["versionId"] = $input["VersionId"];
        }
        if (\array_key_exists("Bucket", $input)) {
            $uri["Bucket"] = $input["Bucket"];
        }
        if (\array_key_exists("Key", $input)) {
            $uri["Key"] = $input["Key"];
        }
        $xmlConfig = ["AccessControlPolicy" => ["type" => 'structure',"members" => ["Grants" => ["shape" => 'Grants',"locationName" => 'AccessControlList'],"Owner" => ["shape" => 'Owner']]],"Grants" => ["type" => 'list',"member" => ["shape" => 'Grant',"locationName" => 'Grant']],"Grant" => ["type" => 'structure',"members" => ["Grantee" => ["shape" => 'Grantee'],"Permission" => ["shape" => 'Permission']]],"Grantee" => ["type" => 'structure',"required" => [0 => 'Type'],"members" => ["DisplayName" => ["shape" => 'DisplayName'],"EmailAddress" => ["shape" => 'EmailAddress'],"ID" => ["shape" => 'ID'],"Type" => ["shape" => 'Type',"locationName" => 'xsi:type',"xmlAttribute" => '1'],"URI" => ["shape" => 'URI']],"xmlNamespace" => ["prefix" => 'xsi',"uri" => 'http://www.w3.org/2001/XMLSchema-instance']],"DisplayName" => ["type" => 'string'],"EmailAddress" => ["type" => 'string'],"ID" => ["type" => 'string'],"Type" => ["type" => 'string'],"URI" => ["type" => 'string'],"Permission" => ["type" => 'string'],"Owner" => ["type" => 'structure',"members" => ["DisplayName" => ["shape" => 'DisplayName'],"ID" => ["shape" => 'ID']]],"_root" => ["type" => 'AccessControlPolicy',"xmlName" => 'AccessControlPolicy',"uri" => 'http://s3.amazonaws.com/doc/2006-03-01/']];
        $payload = (new XmlBuilder($input["AccessControlPolicy"], $xmlConfig))->getXml();
        $response = $this->getResponse('PUT', $payload, $headers, $this->getEndpoint($uri, $query));

        return new PutObjectAclOutput($response);
    }

    /**
     * @see http://docs.amazonwebservices.com/AmazonS3/latest/API/RESTObjectGETacl.html
     *
     * @param array{
     *   Bucket: string
     *   Key: string
     *   VersionId?: string
     *   RequestPayer?: string
     * } $input
     */
    public function getObjectAcl(array $input): GetObjectAclOutput
    {
        $uri = [];
        $query = [];
        $headers = [];
        if (\array_key_exists("RequestPayer", $input)) {
            $headers["x-amz-request-payer"] = $input["RequestPayer"];
        }
        if (\array_key_exists("VersionId", $input)) {
            $query["versionId"] = $input["VersionId"];
        }
        if (\array_key_exists("Bucket", $input)) {
            $uri["Bucket"] = $input["Bucket"];
        }
        if (\array_key_exists("Key", $input)) {
            $uri["Key"] = $input["Key"];
        }
        $payload = "";
        $response = $this->getResponse('GET', $payload, $headers, $this->getEndpoint($uri, $query));

        return new GetObjectAclOutput($response);
    }

    /**
     * @see http://docs.amazonwebservices.com/AmazonS3/latest/API/RESTBucketGET.html
     *
     * @param array{
     *   Bucket: string
     *   Delimiter?: string
     *   EncodingType?: string
     *   Marker?: string
     *   MaxKeys?: int
     *   Prefix?: string
     *   RequestPayer?: string
     * } $input
     */
    public function listObjects(array $input): ListObjectsOutput
    {
        $uri = [];
        $query = [];
        $headers = [];
        if (\array_key_exists("RequestPayer", $input)) {
            $headers["x-amz-request-payer"] = $input["RequestPayer"];
        }
        if (\array_key_exists("Delimiter", $input)) {
            $query["delimiter"] = $input["Delimiter"];
        }
        if (\array_key_exists("EncodingType", $input)) {
            $query["encoding-type"] = $input["EncodingType"];
        }
        if (\array_key_exists("Marker", $input)) {
            $query["marker"] = $input["Marker"];
        }
        if (\array_key_exists("MaxKeys", $input)) {
            $query["max-keys"] = $input["MaxKeys"];
        }
        if (\array_key_exists("Prefix", $input)) {
            $query["prefix"] = $input["Prefix"];
        }
        if (\array_key_exists("Bucket", $input)) {
            $uri["Bucket"] = $input["Bucket"];
        }
        $payload = "";
        $response = $this->getResponse('GET', $payload, $headers, $this->getEndpoint($uri, $query));

        return new ListObjectsOutput($response);
    }
}
