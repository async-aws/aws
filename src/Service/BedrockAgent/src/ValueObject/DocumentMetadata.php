<?php

namespace AsyncAws\BedrockAgent\ValueObject;

use AsyncAws\BedrockAgent\Enum\MetadataSourceType;
use AsyncAws\Core\Exception\InvalidArgument;

/**
 * Contains information about the metadata associate with the content to ingest into a knowledge base. Choose a `type`
 * and include the field that corresponds to it.
 */
final class DocumentMetadata
{
    /**
     * The type of the source source from which to add metadata.
     *
     * @var MetadataSourceType::*
     */
    private $type;

    /**
     * An array of objects, each of which defines a metadata attribute to associate with the content to ingest. You define
     * the attributes inline.
     *
     * @var MetadataAttribute[]|null
     */
    private $inlineAttributes;

    /**
     * The Amazon S3 location of the file containing metadata to associate with the content to ingest.
     *
     * @var CustomS3Location|null
     */
    private $s3Location;

    /**
     * @param array{
     *   type: MetadataSourceType::*,
     *   inlineAttributes?: array<MetadataAttribute|array>|null,
     *   s3Location?: CustomS3Location|array|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->type = $input['type'] ?? $this->throwException(new InvalidArgument('Missing required field "type".'));
        $this->inlineAttributes = isset($input['inlineAttributes']) ? array_map([MetadataAttribute::class, 'create'], $input['inlineAttributes']) : null;
        $this->s3Location = isset($input['s3Location']) ? CustomS3Location::create($input['s3Location']) : null;
    }

    /**
     * @param array{
     *   type: MetadataSourceType::*,
     *   inlineAttributes?: array<MetadataAttribute|array>|null,
     *   s3Location?: CustomS3Location|array|null,
     * }|DocumentMetadata $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return MetadataAttribute[]
     */
    public function getInlineAttributes(): array
    {
        return $this->inlineAttributes ?? [];
    }

    public function getS3Location(): ?CustomS3Location
    {
        return $this->s3Location;
    }

    /**
     * @return MetadataSourceType::*
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        $v = $this->type;
        if (!MetadataSourceType::exists($v)) {
            /** @psalm-suppress NoValue */
            throw new InvalidArgument(\sprintf('Invalid parameter "type" for "%s". The value "%s" is not a valid "MetadataSourceType".', __CLASS__, $v));
        }
        $payload['type'] = $v;
        if (null !== $v = $this->inlineAttributes) {
            $index = -1;
            $payload['inlineAttributes'] = [];
            foreach ($v as $listValue) {
                ++$index;
                $payload['inlineAttributes'][$index] = $listValue->requestBody();
            }
        }
        if (null !== $v = $this->s3Location) {
            $payload['s3Location'] = $v->requestBody();
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
