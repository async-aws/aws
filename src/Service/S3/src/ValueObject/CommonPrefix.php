<?php

namespace AsyncAws\S3\ValueObject;

class CommonPrefix
{
    /**
     * Container for the specified common prefix.
     */
    private $Prefix;

    /**
     * @param array{
     *   Prefix?: null|string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->Prefix = $input['Prefix'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getPrefix(): ?string
    {
        return $this->Prefix;
    }

    public function validate(): void
    {
        // There are no required properties
    }
}
