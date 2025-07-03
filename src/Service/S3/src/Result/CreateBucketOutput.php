<?php

namespace AsyncAws\S3\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Result;

class CreateBucketOutput extends Result
{
    /**
     * A forward slash followed by the name of the bucket.
     *
     * @var string|null
     */
    private $location;

    /**
     * The Amazon Resource Name (ARN) of the S3 bucket. ARNs uniquely identify Amazon Web Services resources across all of
     * Amazon Web Services.
     *
     * > This parameter is only supported for S3 directory buckets. For more information, see Using tags with directory
     * > buckets [^1].
     *
     * [^1]: https://docs.aws.amazon.com/AmazonS3/latest/userguide/directory-buckets-tagging.html
     *
     * @var string|null
     */
    private $bucketArn;

    public function getBucketArn(): ?string
    {
        $this->initialize();

        return $this->bucketArn;
    }

    public function getLocation(): ?string
    {
        $this->initialize();

        return $this->location;
    }

    protected function populateResult(Response $response): void
    {
        $headers = $response->getHeaders();

        $this->location = $headers['location'][0] ?? null;
        $this->bucketArn = $headers['x-amz-bucket-arn'][0] ?? null;
    }
}
