<?php

namespace AsyncAws\BedrockRuntime\ValueObject;

use AsyncAws\BedrockRuntime\Enum\GuardrailConverseImageFormat;
use AsyncAws\Core\Exception\InvalidArgument;

/**
 * An image block that contains images that you want to assess with a guardrail.
 */
final class GuardrailConverseImageBlock
{
    /**
     * The format details for the image type of the guardrail converse image block.
     *
     * @var GuardrailConverseImageFormat::*
     */
    private $format;

    /**
     * The image source (image bytes) of the guardrail converse image block.
     *
     * @var GuardrailConverseImageSource
     */
    private $source;

    /**
     * @param array{
     *   format: GuardrailConverseImageFormat::*,
     *   source: GuardrailConverseImageSource|array,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->format = $input['format'] ?? $this->throwException(new InvalidArgument('Missing required field "format".'));
        $this->source = isset($input['source']) ? GuardrailConverseImageSource::create($input['source']) : $this->throwException(new InvalidArgument('Missing required field "source".'));
    }

    /**
     * @param array{
     *   format: GuardrailConverseImageFormat::*,
     *   source: GuardrailConverseImageSource|array,
     * }|GuardrailConverseImageBlock $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return GuardrailConverseImageFormat::*
     */
    public function getFormat(): string
    {
        return $this->format;
    }

    public function getSource(): GuardrailConverseImageSource
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
        if (!GuardrailConverseImageFormat::exists($v)) {
            throw new InvalidArgument(\sprintf('Invalid parameter "format" for "%s". The value "%s" is not a valid "GuardrailConverseImageFormat".', __CLASS__, $v));
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
