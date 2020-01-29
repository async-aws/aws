<?php

declare(strict_types=1);

namespace WorkingTitle\Aws\S3;

use WorkingTitle\Aws\AbstractApi;
use WorkingTitle\Aws\ResultPromise;
use WorkingTitle\Aws\S3\Result\GetObjectResult;

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
}
