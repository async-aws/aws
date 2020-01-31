<?php

namespace AsyncAws\S3;

use AsyncAws\Core\AbstractApi;
use AsyncAws\S3\Result\CreateBucketOutput;
use AsyncAws\S3\Result\DeleteObjectOutput;
use AsyncAws\S3\Result\GetObjectAclOutput;
use AsyncAws\S3\Result\GetObjectOutput;
use AsyncAws\S3\Result\GetObjectResult;
use AsyncAws\S3\Result\HeadObjectOutput;
use AsyncAws\S3\Result\ListObjectsOutput;
use AsyncAws\S3\Result\PutObjectAclOutput;
use AsyncAws\S3\Result\PutObjectOutput;

class S3Client extends AbstractApi
{
    /**
     * @param string $path The resource you want to get. Eg "/foo/file.png"
     */
    public function getObject(string $bucket, string $path): GetObjectResult
    {
        $headers = [/*auth*/];
        $response = $this->getResponse('GET', '', $headers, $this->getEndpoint($bucket, $path));

        return new GetObjectResult($response);
    }

    protected function getServiceCode(): string
    {
        return 's3';
    }

    private function getEndpoint(string $bucket, string $path): string
    {
        return \sprintf('https://%s.s3.%%region%%.amazonaws.com%s', $bucket, $path);
    }

    /**
     * @see http://docs.amazonwebservices.com/AmazonS3/latest/API/RESTObjectPUT.html
     */
    public function putObject(array $input): PutObjectOutput
    {
        $input['Action'] = 'PutObject';
        $response = $this->getResponse('PUT', $input);

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
     * @see http://docs.amazonwebservices.com/AmazonS3/latest/API/RESTObjectPUTacl.html
     */
    public function putObjectAcl(array $input): PutObjectAclOutput
    {
        $input['Action'] = 'PutObjectAcl';
        $response = $this->getResponse('PUT', $input);
        return new PutObjectAclOutput($response);
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
     * @see http://docs.amazonwebservices.com/AmazonS3/latest/API/RESTObjectGET.html
     */
    public function getObject(array $input): GetObjectOutput
    {
        $input['Action'] = 'GetObject';
        $response = $this->getResponse('GET', $input);
        return new GetObjectOutput($response);
    }
}
