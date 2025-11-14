<?php

namespace AsyncAws\BedrockAgent\ValueObject;

use AsyncAws\BedrockAgent\Enum\CustomSourceType;
use AsyncAws\Core\Exception\InvalidArgument;

/**
 * Contains information about the content to ingest into a knowledge base connected to a custom data source. Choose a
 * `sourceType` and include the field that corresponds to it.
 */
final class CustomContent
{
    /**
     * A unique identifier for the document.
     *
     * @var CustomDocumentIdentifier
     */
    private $customDocumentIdentifier;

    /**
     * The source of the data to ingest.
     *
     * @var CustomSourceType::*
     */
    private $sourceType;

    /**
     * Contains information about the Amazon S3 location of the file from which to ingest data.
     *
     * @var CustomS3Location|null
     */
    private $s3Location;

    /**
     * Contains information about content defined inline to ingest into a knowledge base.
     *
     * @var InlineContent|null
     */
    private $inlineContent;

    /**
     * @param array{
     *   customDocumentIdentifier: CustomDocumentIdentifier|array,
     *   sourceType: CustomSourceType::*,
     *   s3Location?: CustomS3Location|array|null,
     *   inlineContent?: InlineContent|array|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->customDocumentIdentifier = isset($input['customDocumentIdentifier']) ? CustomDocumentIdentifier::create($input['customDocumentIdentifier']) : $this->throwException(new InvalidArgument('Missing required field "customDocumentIdentifier".'));
        $this->sourceType = $input['sourceType'] ?? $this->throwException(new InvalidArgument('Missing required field "sourceType".'));
        $this->s3Location = isset($input['s3Location']) ? CustomS3Location::create($input['s3Location']) : null;
        $this->inlineContent = isset($input['inlineContent']) ? InlineContent::create($input['inlineContent']) : null;
    }

    /**
     * @param array{
     *   customDocumentIdentifier: CustomDocumentIdentifier|array,
     *   sourceType: CustomSourceType::*,
     *   s3Location?: CustomS3Location|array|null,
     *   inlineContent?: InlineContent|array|null,
     * }|CustomContent $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getCustomDocumentIdentifier(): CustomDocumentIdentifier
    {
        return $this->customDocumentIdentifier;
    }

    public function getInlineContent(): ?InlineContent
    {
        return $this->inlineContent;
    }

    public function getS3Location(): ?CustomS3Location
    {
        return $this->s3Location;
    }

    /**
     * @return CustomSourceType::*
     */
    public function getSourceType(): string
    {
        return $this->sourceType;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        $v = $this->customDocumentIdentifier;
        $payload['customDocumentIdentifier'] = $v->requestBody();
        $v = $this->sourceType;
        if (!CustomSourceType::exists($v)) {
            throw new InvalidArgument(\sprintf('Invalid parameter "sourceType" for "%s". The value "%s" is not a valid "CustomSourceType".', __CLASS__, $v));
        }
        $payload['sourceType'] = $v;
        if (null !== $v = $this->s3Location) {
            $payload['s3Location'] = $v->requestBody();
        }
        if (null !== $v = $this->inlineContent) {
            $payload['inlineContent'] = $v->requestBody();
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
