<?php

namespace AsyncAws\S3\Result;

class CommonPrefix
{
    private $Prefix;

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @param array{
     *   Prefix?: string,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->Prefix = $input['Prefix'] ?? null;
    }

    public function getPrefix(): ?string
    {
        return $this->Prefix;
    }
}
