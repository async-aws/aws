<?php

namespace AsyncAws\RDSDataService\ValueObject;

final class UpdateResult
{
    /**
     * Values for fields generated during the request.
     */
    private $generatedFields;

    /**
     * @param array{
     *   generatedFields?: null|array,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->generatedFields = $input['generatedFields'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getGeneratedFields(): ?array
    {
        return $this->generatedFields;
    }
}
