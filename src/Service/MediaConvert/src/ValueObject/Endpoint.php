<?php

namespace AsyncAws\MediaConvert\ValueObject;

/**
 * Describes an account-specific API endpoint.
 */
final class Endpoint
{
    /**
     * URL of endpoint.
     *
     * @var string|null
     */
    private $url;

    /**
     * @param array{
     *   Url?: string|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->url = $input['Url'] ?? null;
    }

    /**
     * @param array{
     *   Url?: string|null,
     * }|Endpoint $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }
}
