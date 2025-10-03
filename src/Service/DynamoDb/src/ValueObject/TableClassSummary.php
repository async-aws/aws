<?php

namespace AsyncAws\DynamoDb\ValueObject;

use AsyncAws\DynamoDb\Enum\TableClass;

/**
 * Contains details of the table class.
 */
final class TableClassSummary
{
    /**
     * The table class of the specified table. Valid values are `STANDARD` and `STANDARD_INFREQUENT_ACCESS`.
     *
     * @var TableClass::*|null
     */
    private $tableClass;

    /**
     * The date and time at which the table class was last updated.
     *
     * @var \DateTimeImmutable|null
     */
    private $lastUpdateDateTime;

    /**
     * @param array{
     *   TableClass?: TableClass::*|null,
     *   LastUpdateDateTime?: \DateTimeImmutable|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->tableClass = $input['TableClass'] ?? null;
        $this->lastUpdateDateTime = $input['LastUpdateDateTime'] ?? null;
    }

    /**
     * @param array{
     *   TableClass?: TableClass::*|null,
     *   LastUpdateDateTime?: \DateTimeImmutable|null,
     * }|TableClassSummary $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getLastUpdateDateTime(): ?\DateTimeImmutable
    {
        return $this->lastUpdateDateTime;
    }

    /**
     * @return TableClass::*|null
     */
    public function getTableClass(): ?string
    {
        return $this->tableClass;
    }
}
