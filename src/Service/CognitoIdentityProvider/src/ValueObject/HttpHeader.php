<?php

namespace AsyncAws\CognitoIdentityProvider\ValueObject;

/**
 * The HTTP header.
 */
final class HttpHeader
{
    /**
     * The header name.
     *
     * @var string|null
     */
    private $headerName;

    /**
     * The header value.
     *
     * @var string|null
     */
    private $headerValue;

    /**
     * @param array{
     *   headerName?: null|string,
     *   headerValue?: null|string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->headerName = $input['headerName'] ?? null;
        $this->headerValue = $input['headerValue'] ?? null;
    }

    /**
     * @param array{
     *   headerName?: null|string,
     *   headerValue?: null|string,
     * }|HttpHeader $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getHeaderName(): ?string
    {
        return $this->headerName;
    }

    public function getHeaderValue(): ?string
    {
        return $this->headerValue;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->headerName) {
            $payload['headerName'] = $v;
        }
        if (null !== $v = $this->headerValue) {
            $payload['headerValue'] = $v;
        }

        return $payload;
    }
}
