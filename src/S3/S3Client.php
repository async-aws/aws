<?php

namespace AsyncAws\S3;

use AsyncAws\Aws\AbstractApi;
use AsyncAws\Aws\ResultPromise;
use AsyncAws\Aws\S3\Result\GetObjectResult;

class S3Client extends AbstractApi
{
    /**
     * @param string $path The resource you want to get. Eg "/foo/file.png"
     *
     * @return ResultPromise<GetObjectResult>
     */
    public function getObject(string $bucket, string $path): ResultPromise
    {
        $headers = [/*auth*/];
                        $response = $this->getResponse('GET', '', $headers, $this->getEndpoint($bucket, $path));

                        return new ResultPromise($response, GetObjectResult::class);
    }

    protected function getServiceCode(): string
    {
        return 's3';
    }

    private function getEndpoint(string $bucket, string $path): string
    {
        return sprintf('https://%s.s3.%%region%%.amazonaws.com%s', $bucket, $path);
    }

    /**
     * @link http://docs.amazonwebservices.com/AmazonS3/latest/API/RESTBucketPUT.html
     */
    public function createBucket(array $input): Result\CreateBucketOutput
    {
        $input['Action'] = 'CreateBucket';
                $response = $this->getResponse('PUT', $input);
                return new \AsyncAws\S3\Result\CreateBucketOutput($response);
    }

    /**
     * @link http://docs.amazonwebservices.com/AmazonS3/latest/API/RESTObjectPUT.html
     */
    public function putObject(array $input): Result\PutObjectOutput
    {
        $input['Action'] = 'PutObject';
        $response = $this->getResponse('PUT', $input);
        return new \AsyncAws\S3\Result\PutObjectOutput($response);
    }
}
