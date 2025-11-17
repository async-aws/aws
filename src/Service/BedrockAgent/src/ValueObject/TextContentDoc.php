<?php

namespace AsyncAws\BedrockAgent\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;

/**
 * Contains information about content defined inline in text.
 */
final class TextContentDoc
{
    /**
     * The text of the content.
     *
     * @var string
     */
    private $data;

    /**
     * @param array{
     *   data: string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->data = $input['data'] ?? $this->throwException(new InvalidArgument('Missing required field "data".'));
    }

    /**
     * @param array{
     *   data: string,
     * }|TextContentDoc $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getData(): string
    {
        return $this->data;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        $v = $this->data;
        $payload['data'] = $v;

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
