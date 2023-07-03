<?php

namespace AsyncAws\Ses\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;

/**
 * An object that represents the content of the email, and optionally a character set specification.
 */
final class Content
{
    /**
     * The content of the message itself.
     *
     * @var string
     */
    private $data;

    /**
     * The character set for the content. Because of the constraints of the SMTP protocol, Amazon SES uses 7-bit ASCII by
     * default. If the text includes characters outside of the ASCII range, you have to specify a character set. For
     * example, you could specify `UTF-8`, `ISO-8859-1`, or `Shift_JIS`.
     *
     * @var string|null
     */
    private $charset;

    /**
     * @param array{
     *   Data: string,
     *   Charset?: null|string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->data = $input['Data'] ?? $this->throwException(new InvalidArgument('Missing required field "Data".'));
        $this->charset = $input['Charset'] ?? null;
    }

    /**
     * @param array{
     *   Data: string,
     *   Charset?: null|string,
     * }|Content $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getCharset(): ?string
    {
        return $this->charset;
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
        $payload['Data'] = $v;
        if (null !== $v = $this->charset) {
            $payload['Charset'] = $v;
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
