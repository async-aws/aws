<?php

namespace AsyncAws\S3\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;

/**
 * Specifies a cross-origin access rule for an Amazon S3 bucket.
 */
final class CORSRule
{
    /**
     * Unique identifier for the rule. The value cannot be longer than 255 characters.
     *
     * @var string|null
     */
    private $id;

    /**
     * Headers that are specified in the `Access-Control-Request-Headers` header. These headers are allowed in a preflight
     * OPTIONS request. In response to any preflight OPTIONS request, Amazon S3 returns any requested headers that are
     * allowed.
     *
     * @var string[]|null
     */
    private $allowedHeaders;

    /**
     * An HTTP method that you allow the origin to execute. Valid values are `GET`, `PUT`, `HEAD`, `POST`, and `DELETE`.
     *
     * @var string[]
     */
    private $allowedMethods;

    /**
     * One or more origins you want customers to be able to access the bucket from.
     *
     * @var string[]
     */
    private $allowedOrigins;

    /**
     * One or more headers in the response that you want customers to be able to access from their applications (for
     * example, from a JavaScript `XMLHttpRequest` object).
     *
     * @var string[]|null
     */
    private $exposeHeaders;

    /**
     * The time in seconds that your browser is to cache the preflight response for the specified resource.
     *
     * @var int|null
     */
    private $maxAgeSeconds;

    /**
     * @param array{
     *   ID?: null|string,
     *   AllowedHeaders?: null|string[],
     *   AllowedMethods: string[],
     *   AllowedOrigins: string[],
     *   ExposeHeaders?: null|string[],
     *   MaxAgeSeconds?: null|int,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->id = $input['ID'] ?? null;
        $this->allowedHeaders = $input['AllowedHeaders'] ?? null;
        $this->allowedMethods = $input['AllowedMethods'] ?? $this->throwException(new InvalidArgument('Missing required field "AllowedMethods".'));
        $this->allowedOrigins = $input['AllowedOrigins'] ?? $this->throwException(new InvalidArgument('Missing required field "AllowedOrigins".'));
        $this->exposeHeaders = $input['ExposeHeaders'] ?? null;
        $this->maxAgeSeconds = $input['MaxAgeSeconds'] ?? null;
    }

    /**
     * @param array{
     *   ID?: null|string,
     *   AllowedHeaders?: null|string[],
     *   AllowedMethods: string[],
     *   AllowedOrigins: string[],
     *   ExposeHeaders?: null|string[],
     *   MaxAgeSeconds?: null|int,
     * }|CORSRule $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return string[]
     */
    public function getAllowedHeaders(): array
    {
        return $this->allowedHeaders ?? [];
    }

    /**
     * @return string[]
     */
    public function getAllowedMethods(): array
    {
        return $this->allowedMethods;
    }

    /**
     * @return string[]
     */
    public function getAllowedOrigins(): array
    {
        return $this->allowedOrigins;
    }

    /**
     * @return string[]
     */
    public function getExposeHeaders(): array
    {
        return $this->exposeHeaders ?? [];
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getMaxAgeSeconds(): ?int
    {
        return $this->maxAgeSeconds;
    }

    /**
     * @internal
     */
    public function requestBody(\DOMElement $node, \DOMDocument $document): void
    {
        if (null !== $v = $this->id) {
            $node->appendChild($document->createElement('ID', $v));
        }
        if (null !== $v = $this->allowedHeaders) {
            foreach ($v as $item) {
                $node->appendChild($document->createElement('AllowedHeader', $item));
            }
        }
        $v = $this->allowedMethods;
        foreach ($v as $item) {
            $node->appendChild($document->createElement('AllowedMethod', $item));
        }

        $v = $this->allowedOrigins;
        foreach ($v as $item) {
            $node->appendChild($document->createElement('AllowedOrigin', $item));
        }

        if (null !== $v = $this->exposeHeaders) {
            foreach ($v as $item) {
                $node->appendChild($document->createElement('ExposeHeader', $item));
            }
        }
        if (null !== $v = $this->maxAgeSeconds) {
            $node->appendChild($document->createElement('MaxAgeSeconds', (string) $v));
        }
    }

    /**
     * @return never
     */
    private function throwException(\Throwable $exception)
    {
        throw $exception;
    }
}
