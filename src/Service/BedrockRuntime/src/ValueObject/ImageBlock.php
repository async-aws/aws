<?php

namespace AsyncAws\BedrockRuntime\ValueObject;

use AsyncAws\BedrockRuntime\Enum\ImageFormat;
use AsyncAws\Core\Exception\InvalidArgument;

/**
 * Image content for a message.
 */
final class ImageBlock
{
    /**
     * The format of the image.
     *
     * @var ImageFormat::*
     */
    private $format;

    /**
     * The source for the image.
     *
     * @var ImageSource
     */
    private $source;

    /**
     * @param array{
     *   format: ImageFormat::*,
     *   source: ImageSource|array,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->format = $input['format'] ?? $this->throwException(new InvalidArgument('Missing required field "format".'));
        $this->source = isset($input['source']) ? ImageSource::create($input['source']) : $this->throwException(new InvalidArgument('Missing required field "source".'));
    }

    /**
     * @param array{
     *   format: ImageFormat::*,
     *   source: ImageSource|array,
     * }|ImageBlock $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return ImageFormat::*
     */
    public function getFormat(): string
    {
        return $this->format;
    }

    public function getSource(): ImageSource
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
        if (!ImageFormat::exists($v)) {
            throw new InvalidArgument(\sprintf('Invalid parameter "format" for "%s". The value "%s" is not a valid "ImageFormat".', __CLASS__, $v));
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
