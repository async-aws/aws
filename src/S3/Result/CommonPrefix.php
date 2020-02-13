<?php

namespace AsyncAws\S3\Result;

class CommonPrefix
{
    /**
     * Container for the specified common prefix.
     */
    private $Prefix;

    /**
     * @param array{
     *   Prefix: ?string,
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

    public function getPrefix(): ?string
    {
        return $this->Prefix;
    }
}
