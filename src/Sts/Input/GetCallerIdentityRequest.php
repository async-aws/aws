<?php

namespace AsyncAws\Sts\Input;

class GetCallerIdentityRequest
{
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @param array{
     * } $input
     */
    public function __construct(array $input = [])
    {
    }

    public function requestHeaders(): array
    {
        $headers = [];

        return $headers;
    }

    public function requestQuery(): array
    {
        $query = [];

        return $query;
    }

    public function requestBody(): array
    {
        $payload = ['Action' => 'GetCallerIdentity'];

        return $payload;
    }

    public function requestUri(): string
    {
        return '/';
    }

    public function validate(): void
    {
        // There are no required properties
    }
}
