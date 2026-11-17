<?php

namespace AsyncAws\S3Vectors\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\S3Vectors\Enum\AnalyzerStatus;
use AsyncAws\S3Vectors\Enum\LanguageCode;

/**
 * A full-text search configuration for a single metadata key.
 */
final class TextSearchConfiguration
{
    /**
     * The metadata key to make full-text searchable.
     *
     * @var string
     */
    private $metadataKey;

    /**
     * The language that the text search analyzer uses. Valid values are `en` (English), `fr` (French), `es` (Spanish), `pt`
     * (Portuguese), `it` (Italian), `de` (German), `nl` (Dutch), `zh` (Chinese), `ja` (Japanese), and `ko` (Korean). If you
     * don't specify a language, Amazon S3 Vectors uses the default standard analyzer.
     *
     * @var LanguageCode::*|null
     */
    private $language;

    /**
     * The backfill status of the text search configuration. Valid values are `ACTIVE`, which indicates that the analyzer is
     * built and serving queries, and `BUILDING`, which indicates that the analyzer is being backfilled and isn't yet
     * serving queries. This field is returned by `GetIndex`.
     *
     * @var AnalyzerStatus::*|null
     */
    private $status;

    /**
     * @param array{
     *   metadataKey: string,
     *   language?: LanguageCode::*|null,
     *   status?: AnalyzerStatus::*|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->metadataKey = $input['metadataKey'] ?? $this->throwException(new InvalidArgument('Missing required field "metadataKey".'));
        $this->language = $input['language'] ?? null;
        $this->status = $input['status'] ?? null;
    }

    /**
     * @param array{
     *   metadataKey: string,
     *   language?: LanguageCode::*|null,
     *   status?: AnalyzerStatus::*|null,
     * }|TextSearchConfiguration $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return LanguageCode::*|null
     */
    public function getLanguage(): ?string
    {
        return $this->language;
    }

    public function getMetadataKey(): string
    {
        return $this->metadataKey;
    }

    /**
     * @return AnalyzerStatus::*|null
     */
    public function getStatus(): ?string
    {
        return $this->status;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        $v = $this->metadataKey;
        $payload['metadataKey'] = $v;
        if (null !== $v = $this->language) {
            if (!LanguageCode::exists($v)) {
                /** @psalm-suppress NoValue */
                throw new InvalidArgument(\sprintf('Invalid parameter "language" for "%s". The value "%s" is not a valid "LanguageCode".', __CLASS__, $v));
            }
            $payload['language'] = $v;
        }
        if (null !== $v = $this->status) {
            if (!AnalyzerStatus::exists($v)) {
                /** @psalm-suppress NoValue */
                throw new InvalidArgument(\sprintf('Invalid parameter "status" for "%s". The value "%s" is not a valid "AnalyzerStatus".', __CLASS__, $v));
            }
            $payload['status'] = $v;
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
