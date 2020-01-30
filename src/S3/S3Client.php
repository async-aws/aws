<?php

namespace AsyncAws\S3;

use AsyncAws\Aws\AbstractApi;
use AsyncAws\Aws\Result;
use AsyncAws\S3\Result\CreateBucketOutput;
use AsyncAws\S3\Result\GetObjectResult;
use AsyncAws\S3\Result\PutObjectOutput;

class S3Client extends AbstractApi
{
    /**
     * @param string $path The resource you want to get. Eg "/foo/file.png"
     *
     * @return Result<GetObjectResult>
     */
    public function getObject(string $bucket, string $path): Result
    {
        $headers = [/*auth*/];
        $response = $this->getResponse('GET', '', $headers, $this->getEndpoint($bucket, $path));

        return new Result($response, GetObjectResult::class);
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
}
