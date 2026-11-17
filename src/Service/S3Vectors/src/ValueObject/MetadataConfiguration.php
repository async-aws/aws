<?php

namespace AsyncAws\S3Vectors\ValueObject;

/**
 * The metadata configuration for a vector index.
 */
final class MetadataConfiguration
{
    /**
     * Non-filterable metadata keys allow you to enrich vectors with additional context during storage and retrieval. You
     * can't use these keys in metadata filters. You can still make a non-filterable key full-text searchable by configuring
     * it separately in `textSearchConfigurations`. You can retrieve non-filterable metadata keys after finding the vectors.
     * For more information about non-filterable metadata keys, see Vectors [^1] and Limitations and restrictions [^2] in
     * the *Amazon S3 User Guide*.
     *
     * [^1]: https://docs.aws.amazon.com/AmazonS3/latest/userguide/s3-vectors-vectors.html
     * [^2]: https://docs.aws.amazon.com/AmazonS3/latest/userguide/s3-vectors-limitations.html
     *
     * @var string[]|null
     */
    private $nonFilterableMetadataKeys;

    /**
     * Full-text search configurations for searchable metadata keys.
     *
     * @var TextSearchConfiguration[]|null
     */
    private $textSearchConfigurations;

    /**
     * @param array{
     *   nonFilterableMetadataKeys?: string[]|null,
     *   textSearchConfigurations?: array<TextSearchConfiguration|array>|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->nonFilterableMetadataKeys = $input['nonFilterableMetadataKeys'] ?? null;
        $this->textSearchConfigurations = isset($input['textSearchConfigurations']) ? array_map([TextSearchConfiguration::class, 'create'], $input['textSearchConfigurations']) : null;
    }

    /**
     * @param array{
     *   nonFilterableMetadataKeys?: string[]|null,
     *   textSearchConfigurations?: array<TextSearchConfiguration|array>|null,
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
        return $this->nonFilterableMetadataKeys ?? [];
    }

    /**
     * @return TextSearchConfiguration[]
     */
    public function getTextSearchConfigurations(): array
    {
        return $this->textSearchConfigurations ?? [];
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->nonFilterableMetadataKeys) {
            $index = -1;
            $payload['nonFilterableMetadataKeys'] = [];
            foreach ($v as $listValue) {
                ++$index;
                $payload['nonFilterableMetadataKeys'][$index] = $listValue;
            }
        }
        if (null !== $v = $this->textSearchConfigurations) {
            $index = -1;
            $payload['textSearchConfigurations'] = [];
            foreach ($v as $listValue) {
                ++$index;
                $payload['textSearchConfigurations'][$index] = $listValue->requestBody();
            }
        }

        return $payload;
    }
}
