<?php

namespace AsyncAws\S3;

use AsyncAws\Core\AbstractApi;
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
     * @see http://docs.amazonwebservices.com/AmazonS3/latest/API/RESTObjectGET.html
     */
    public function getObject(array $input): GetObjectOutput
    {
        $input['Action'] = 'GetObject';
        $response = $this->getResponse('GET', $input);

        return new GetObjectOutput($response);
    }

    protected function getServiceCode(): string
    {
        return 's3';
    }

    protected function getEndpoint(array $data): ?string
    {
        return \sprintf('https://%s.s3.%%region%%.amazonaws.com/%s', $data['Bucket'], ltrim($data['Key'], '/'));
    }

    /**
     * @see http://docs.amazonwebservices.com/AmazonS3/latest/API/RESTBucketPUT.html
     */
    public function createBucket(array $input): CreateBucketOutput
    {
        $input['Action'] = 'CreateBucket';
        $response = $this->getResponse('PUT', $input);

        return new CreateBucketOutput($response);
    }

    /**
     * @see http://docs.amazonwebservices.com/AmazonS3/latest/API/RESTObjectGETacl.html
     */
    public function getObjectAcl(array $input): GetObjectAclOutput
    {
        $input['Action'] = 'GetObjectAcl';
        $response = $this->getResponse('GET', $input);

        return new GetObjectAclOutput($response);
    }

    /**
     * @see http://docs.amazonwebservices.com/AmazonS3/latest/API/RESTBucketGET.html
     */
    public function listObjects(array $input): ListObjectsOutput
    {
        $input['Action'] = 'ListObjects';
        $response = $this->getResponse('GET', $input);

        return new ListObjectsOutput($response);
    }

    /**
     * @see http://docs.amazonwebservices.com/AmazonS3/latest/API/RESTObjectDELETE.html
     */
    public function deleteObject(array $input): DeleteObjectOutput
    {
        $input['Action'] = 'DeleteObject';
        $response = $this->getResponse('DELETE', $input);

        return new DeleteObjectOutput($response);
    }

    /**
     * @see http://docs.amazonwebservices.com/AmazonS3/latest/API/RESTObjectHEAD.html
     */
    public function headObject(array $input): HeadObjectOutput
    {
        $input['Action'] = 'HeadObject';
        $response = $this->getResponse('HEAD', $input);

        return new HeadObjectOutput($response);
    }

    /**
     * @see http://docs.amazonwebservices.com/AmazonS3/latest/API/RESTObjectCOPY.html
     */
    public function copyObject(array $input): CopyObjectOutput
    {
        $input['Action'] = 'CopyObject';
        $response = $this->getResponse('PUT', $input);

        return new CopyObjectOutput($response);
    }

    /**
     * @see http://docs.amazonwebservices.com/AmazonS3/latest/API/RESTObjectPUTacl.html
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
        $payload = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
        <AccessControlPolicy xmlns="http://s3.amazonaws.com/doc/2006-03-01/">
        <AccessControlList>
        <Grant>
        <Grantee>
        <DisplayName>{$input["Grants"][0]["Grantee"]["DisplayName"]}</DisplayName>
        <EmailAddress>{$input["Grants"][0]["Grantee"]["EmailAddress"]}</EmailAddress>
        <ID>{$input["Grants"][0]["Grantee"]["ID"]}</ID>
        <xsi:type>{$input["Grants"][0]["Grantee"]["Type"]}</xsi:type>
        <URI>{$input["Grants"][0]["Grantee"]["URI"]}</URI>
        </Grantee>
        <Permission>{$input["Grants"][0]["Permission"]}</Permission>
        </Grant>
        </AccessControlList>
        <Owner>
        <DisplayName>{$input["Owner"]["DisplayName"]}</DisplayName>
        <ID>{$input["Owner"]["ID"]}</ID>
        </Owner>
        </AccessControlPolicy>

XML;
        $response = $this->getResponse('PUT', $payload, $headers);

        return new PutObjectAclOutput($response);
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
        $response = $this->getResponse('PUT', $payload, $headers, $this->getEndpoint($uri));

        return new PutObjectOutput($response);
    }
}
