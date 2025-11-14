<?php

namespace AsyncAws\BedrockAgent\ValueObject;

use AsyncAws\BedrockAgent\Enum\ContentDataSourceType;
use AsyncAws\Core\Exception\InvalidArgument;

/**
 * Contains information that identifies the document.
 */
final class DocumentIdentifier
{
    /**
     * The type of data source connected to the knowledge base that contains the document.
     *
     * @var ContentDataSourceType::*
     */
    private $dataSourceType;

    /**
     * Contains information that identifies the document in an S3 data source.
     *
     * @var S3Location|null
     */
    private $s3;

    /**
     * Contains information that identifies the document in a custom data source.
     *
     * @var CustomDocumentIdentifier|null
     */
    private $custom;

    /**
     * @param array{
     *   dataSourceType: ContentDataSourceType::*,
     *   s3?: S3Location|array|null,
     *   custom?: CustomDocumentIdentifier|array|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->dataSourceType = $input['dataSourceType'] ?? $this->throwException(new InvalidArgument('Missing required field "dataSourceType".'));
        $this->s3 = isset($input['s3']) ? S3Location::create($input['s3']) : null;
        $this->custom = isset($input['custom']) ? CustomDocumentIdentifier::create($input['custom']) : null;
    }

    /**
     * @param array{
     *   dataSourceType: ContentDataSourceType::*,
     *   s3?: S3Location|array|null,
     *   custom?: CustomDocumentIdentifier|array|null,
     * }|DocumentIdentifier $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getCustom(): ?CustomDocumentIdentifier
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

    public function getS3(): ?S3Location
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
            throw new InvalidArgument(\sprintf('Invalid parameter "dataSourceType" for "%s". The value "%s" is not a valid "ContentDataSourceType".', __CLASS__, $v));
        }
        $payload['dataSourceType'] = $v;
        if (null !== $v = $this->s3) {
            $payload['s3'] = $v->requestBody();
        }
        if (null !== $v = $this->custom) {
            $payload['custom'] = $v->requestBody();
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
