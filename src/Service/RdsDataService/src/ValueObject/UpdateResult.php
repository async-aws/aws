<?php

namespace AsyncAws\RdsDataService\ValueObject;

/**
 * The response elements represent the results of an update.
 */
final class UpdateResult
{
    /**
     * Values for fields generated during the request.
     */
    private $generatedFields;

    /**
     * @param array{
     *   generatedFields?: null|Field[],
     * } $input
     */
    public function __construct(array $input)
    {
        $this->generatedFields = isset($input['generatedFields']) ? array_map([Field::class, 'create'], $input['generatedFields']) : [];
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return Field[]
     */
    public function getGeneratedFields(): array
    {
        return $this->generatedFields;
    }
}
