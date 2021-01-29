<?php

namespace AsyncAws\S3\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;

/**
 * Specifies a cross-origin access rule for an Amazon S3 bucket.
 */
final class CORSRule
{
    /**
     * Headers that are specified in the `Access-Control-Request-Headers` header. These headers are allowed in a preflight
     * OPTIONS request. In response to any preflight OPTIONS request, Amazon S3 returns any requested headers that are
     * allowed.
     */
    private $AllowedHeaders;

    /**
     * An HTTP method that you allow the origin to execute. Valid values are `GET`, `PUT`, `HEAD`, `POST`, and `DELETE`.
     */
    private $AllowedMethods;

    /**
     * One or more origins you want customers to be able to access the bucket from.
     */
    private $AllowedOrigins;

    /**
     * One or more headers in the response that you want customers to be able to access from their applications (for
     * example, from a JavaScript `XMLHttpRequest` object).
     */
    private $ExposeHeaders;

    /**
     * The time in seconds that your browser is to cache the preflight response for the specified resource.
     */
    private $MaxAgeSeconds;

    /**
     * @param array{
     *   AllowedHeaders?: null|string[],
     *   AllowedMethods: string[],
     *   AllowedOrigins: string[],
     *   ExposeHeaders?: null|string[],
     *   MaxAgeSeconds?: null|int,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->AllowedHeaders = $input['AllowedHeaders'] ?? null;
        $this->AllowedMethods = $input['AllowedMethods'] ?? null;
        $this->AllowedOrigins = $input['AllowedOrigins'] ?? null;
        $this->ExposeHeaders = $input['ExposeHeaders'] ?? null;
        $this->MaxAgeSeconds = $input['MaxAgeSeconds'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return string[]
     */
    public function getAllowedHeaders(): array
    {
        return $this->AllowedHeaders ?? [];
    }

    /**
     * @return string[]
     */
    public function getAllowedMethods(): array
    {
        return $this->AllowedMethods ?? [];
    }

    /**
     * @return string[]
     */
    public function getAllowedOrigins(): array
    {
        return $this->AllowedOrigins ?? [];
    }

    /**
     * @return string[]
     */
    public function getExposeHeaders(): array
    {
        return $this->ExposeHeaders ?? [];
    }

    public function getMaxAgeSeconds(): ?int
    {
        return $this->MaxAgeSeconds;
    }

    /**
     * @internal
     */
    public function requestBody(\DomElement $node, \DomDocument $document): void
    {
        if (null !== $v = $this->AllowedHeaders) {
            foreach ($v as $item) {
                $node->appendChild($document->createElement('AllowedHeader', $item));
            }
        }
        if (null === $v = $this->AllowedMethods) {
            throw new InvalidArgument(sprintf('Missing parameter "AllowedMethods" for "%s". The value cannot be null.', __CLASS__));
        }
        foreach ($v as $item) {
            $node->appendChild($document->createElement('AllowedMethod', $item));
        }

        if (null === $v = $this->AllowedOrigins) {
            throw new InvalidArgument(sprintf('Missing parameter "AllowedOrigins" for "%s". The value cannot be null.', __CLASS__));
        }
        foreach ($v as $item) {
            $node->appendChild($document->createElement('AllowedOrigin', $item));
        }

        if (null !== $v = $this->ExposeHeaders) {
            foreach ($v as $item) {
                $node->appendChild($document->createElement('ExposeHeader', $item));
            }
        }
        if (null !== $v = $this->MaxAgeSeconds) {
            $node->appendChild($document->createElement('MaxAgeSeconds', $v));
        }
    }
}
