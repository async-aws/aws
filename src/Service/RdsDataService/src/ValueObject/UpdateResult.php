<?php

namespace AsyncAws\RdsDataService\ValueObject;

/**
 * The response elements represent the results of an update.
 */
final class UpdateResult
{
    /**
     * Values for fields generated during the request.
     *
     * @var Field[]|null
     */
    private $generatedFields;

    /**
     * @param array{
     *   generatedFields?: array<Field|array>|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->generatedFields = isset($input['generatedFields']) ? array_map([Field::class, 'create'], $input['generatedFields']) : null;
    }

    /**
     * @param array{
     *   generatedFields?: array<Field|array>|null,
     * }|UpdateResult $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return Field[]
     */
    public function getGeneratedFields(): array
    {
        return $this->generatedFields ?? [];
    }
}
