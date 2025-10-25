<?php

namespace AsyncAws\BedrockRuntime\ValueObject;

use AsyncAws\BedrockRuntime\Enum\VideoFormat;
use AsyncAws\Core\Exception\InvalidArgument;

/**
 * A video block.
 */
final class VideoBlock
{
    /**
     * The block's format.
     *
     * @var VideoFormat::*
     */
    private $format;

    /**
     * The block's source.
     *
     * @var VideoSource
     */
    private $source;

    /**
     * @param array{
     *   format: VideoFormat::*,
     *   source: VideoSource|array,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->format = $input['format'] ?? $this->throwException(new InvalidArgument('Missing required field "format".'));
        $this->source = isset($input['source']) ? VideoSource::create($input['source']) : $this->throwException(new InvalidArgument('Missing required field "source".'));
    }

    /**
     * @param array{
     *   format: VideoFormat::*,
     *   source: VideoSource|array,
     * }|VideoBlock $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return VideoFormat::*
     */
    public function getFormat(): string
    {
        return $this->format;
    }

    public function getSource(): VideoSource
    {
        return $this->source;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        $v = $this->format;
        if (!VideoFormat::exists($v)) {
            throw new InvalidArgument(\sprintf('Invalid parameter "format" for "%s". The value "%s" is not a valid "VideoFormat".', __CLASS__, $v));
        }
        $payload['format'] = $v;
        $v = $this->source;
        $payload['source'] = $v->requestBody();

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
