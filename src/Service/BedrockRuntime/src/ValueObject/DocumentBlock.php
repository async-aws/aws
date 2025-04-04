<?php

namespace AsyncAws\BedrockRuntime\ValueObject;

use AsyncAws\BedrockRuntime\Enum\DocumentFormat;
use AsyncAws\Core\Exception\InvalidArgument;

/**
 * A document to include in a message.
 */
final class DocumentBlock
{
    /**
     * The format of a document, or its extension.
     *
     * @var DocumentFormat::*
     */
    private $format;

    /**
     * A name for the document. The name can only contain the following characters:
     *
     * - Alphanumeric characters
     * - Whitespace characters (no more than one in a row)
     * - Hyphens
     * - Parentheses
     * - Square brackets
     *
     * > This field is vulnerable to prompt injections, because the model might inadvertently interpret it as instructions.
     * > Therefore, we recommend that you specify a neutral name.
     *
     * @var string
     */
    private $name;

    /**
     * Contains the content of the document.
     *
     * @var DocumentSource
     */
    private $source;

    /**
     * @param array{
     *   format: DocumentFormat::*,
     *   name: string,
     *   source: DocumentSource|array,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->format = $input['format'] ?? $this->throwException(new InvalidArgument('Missing required field "format".'));
        $this->name = $input['name'] ?? $this->throwException(new InvalidArgument('Missing required field "name".'));
        $this->source = isset($input['source']) ? DocumentSource::create($input['source']) : $this->throwException(new InvalidArgument('Missing required field "source".'));
    }

    /**
     * @param array{
     *   format: DocumentFormat::*,
     *   name: string,
     *   source: DocumentSource|array,
     * }|DocumentBlock $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return DocumentFormat::*
     */
    public function getFormat(): string
    {
        return $this->format;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getSource(): DocumentSource
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
        if (!DocumentFormat::exists($v)) {
            throw new InvalidArgument(\sprintf('Invalid parameter "format" for "%s". The value "%s" is not a valid "DocumentFormat".', __CLASS__, $v));
        }
        $payload['format'] = $v;
        $v = $this->name;
        $payload['name'] = $v;
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
