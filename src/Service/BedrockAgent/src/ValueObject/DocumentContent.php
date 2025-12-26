<?php

namespace AsyncAws\BedrockAgent\ValueObject;

use AsyncAws\BedrockAgent\Enum\ContentDataSourceType;
use AsyncAws\Core\Exception\InvalidArgument;

/**
 * Contains information about the content of a document. Choose a `dataSourceType` and include the field that
 * corresponds to it.
 */
final class DocumentContent
{
    /**
     * The type of data source that is connected to the knowledge base to which to ingest this document.
     *
     * @var ContentDataSourceType::*
     */
    private $dataSourceType;

    /**
     * Contains information about the content to ingest into a knowledge base connected to a custom data source.
     *
     * @var CustomContent|null
     */
    private $custom;

    /**
     * Contains information about the content to ingest into a knowledge base connected to an Amazon S3 data source.
     *
     * @var S3Content|null
     */
    private $s3;

    /**
     * @param array{
     *   dataSourceType: ContentDataSourceType::*,
     *   custom?: CustomContent|array|null,
     *   s3?: S3Content|array|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->dataSourceType = $input['dataSourceType'] ?? $this->throwException(new InvalidArgument('Missing required field "dataSourceType".'));
        $this->custom = isset($input['custom']) ? CustomContent::create($input['custom']) : null;
        $this->s3 = isset($input['s3']) ? S3Content::create($input['s3']) : null;
    }

    /**
     * @param array{
     *   dataSourceType: ContentDataSourceType::*,
     *   custom?: CustomContent|array|null,
     *   s3?: S3Content|array|null,
     * }|DocumentContent $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getCustom(): ?CustomContent
    {
        return $this->custom;
    }

    /**
     * @return ContentDataSourceType::*
     */
    public function getDataSourceType(): string
    {
        return $this->dataSourceType;
    }

    public function getS3(): ?S3Content
    {
        return $this->s3;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        $v = $this->dataSourceType;
        if (!ContentDataSourceType::exists($v)) {
            /** @psalm-suppress NoValue */
            throw new InvalidArgument(\sprintf('Invalid parameter "dataSourceType" for "%s". The value "%s" is not a valid "ContentDataSourceType".', __CLASS__, $v));
        }
        $payload['dataSourceType'] = $v;
        if (null !== $v = $this->custom) {
            $payload['custom'] = $v->requestBody();
        }
        if (null !== $v = $this->s3) {
            $payload['s3'] = $v->requestBody();
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
