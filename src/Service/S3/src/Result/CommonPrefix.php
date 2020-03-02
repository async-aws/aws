<?php

namespace AsyncAws\S3\Result;

class CommonPrefix
{
    private $Prefix;

    /**
     * @param array{
     *   Prefix: null|string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->Prefix = $input['Prefix'];
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * Container for the specified common prefix.
     */
    public function getPrefix(): ?string
    {
        return $this->Prefix;
    }
}
