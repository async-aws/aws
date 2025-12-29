<?php

namespace AsyncAws\S3Vectors\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;

/**
 * The metadata configuration for a vector index.
 */
final class MetadataConfiguration
{
    /**
     * Non-filterable metadata keys allow you to enrich vectors with additional context during storage and retrieval. Unlike
     * default metadata keys, these keys can’t be used as query filters. Non-filterable metadata keys can be retrieved but
     * can’t be searched, queried, or filtered. You can access non-filterable metadata keys of your vectors after finding
     * the vectors. For more information about non-filterable metadata keys, see Vectors [^1] and Limitations and
     * restrictions [^2] in the *Amazon S3 User Guide*.
     *
     * [^1]: https://docs.aws.amazon.com/AmazonS3/latest/userguide/s3-vectors-vectors.html
     * [^2]: https://docs.aws.amazon.com/AmazonS3/latest/userguide/s3-vectors-limitations.html
     *
     * @var string[]
     */
    private $nonFilterableMetadataKeys;

    /**
     * @param array{
     *   nonFilterableMetadataKeys: string[],
     * } $input
     */
    public function __construct(array $input)
    {
        $this->nonFilterableMetadataKeys = $input['nonFilterableMetadataKeys'] ?? $this->throwException(new InvalidArgument('Missing required field "nonFilterableMetadataKeys".'));
    }

    /**
     * @param array{
     *   nonFilterableMetadataKeys: string[],
     * }|MetadataConfiguration $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return string[]
     */
    public function getNonFilterableMetadataKeys(): array
    {
        return $this->nonFilterableMetadataKeys;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        $v = $this->nonFilterableMetadataKeys;

        $index = -1;
        $payload['nonFilterableMetadataKeys'] = [];
        foreach ($v as $listValue) {
            ++$index;
            $payload['nonFilterableMetadataKeys'][$index] = $listValue;
        }

        return $payload;
    }

    /**
     * @return never
     */
    private function throwException(\Throwable $exception)
    {
        throw $exception;
    }
}
