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

    private function getEndpoint(string $bucket, string $path): string
    {
        return \sprintf('https://%s.s3.%%region%%.amazonaws.com/%s', $bucket, ltrim($path, '/'));
    }

    /**
     * @see http://docs.amazonwebservices.com/AmazonS3/latest/API/RESTObjectPUT.html
     */
    public function putObject(array $input): PutObjectOutput
    {
        $body = $input['Body'];
        unset($input['Body']);
        $response = $this->getResponse('PUT', $body, $input, $this->getEndpoint($input['Bucket'], $input['Key']));

        return new PutObjectOutput($response);
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
        $payload = [];
        if (array_key_exists("ACL", $input)) $headers["x-amz-acl"] = $input["ACL"];
        if (array_key_exists("ContentMD5", $input)) $headers["Content-MD5"] = $input["ContentMD5"];
        if (array_key_exists("GrantFullControl", $input)) $headers["x-amz-grant-full-control"] = $input["GrantFullControl"];
        if (array_key_exists("GrantRead", $input)) $headers["x-amz-grant-read"] = $input["GrantRead"];
        if (array_key_exists("GrantReadACP", $input)) $headers["x-amz-grant-read-acp"] = $input["GrantReadACP"];
        if (array_key_exists("GrantWrite", $input)) $headers["x-amz-grant-write"] = $input["GrantWrite"];
        if (array_key_exists("GrantWriteACP", $input)) $headers["x-amz-grant-write-acp"] = $input["GrantWriteACP"];
        if (array_key_exists("RequestPayer", $input)) $headers["x-amz-request-payer"] = $input["RequestPayer"];
        if (array_key_exists("VersionId", $input)) $query["versionId"] = $input["VersionId"];
        if (array_key_exists("Bucket", $input)) $uri["Bucket"] = $input["Bucket"];
        if (array_key_exists("Key", $input)) $uri["Key"] = $input["Key"];

        $response = $this->getResponse('PUT', $input, $headers);
        return new PutObjectAclOutput($response);
    }
}
