<?php

namespace AsyncAws\Athena\ValueObject;

use AsyncAws\Athena\Enum\SystemTableType;
use AsyncAws\Core\Exception\InvalidArgument;

/**
 * The enablement status of a single system table. Because system tables are enabled at the account level, every status
 * reflects the same account-wide state.
 */
final class SystemTableStatus
{
    /**
     * The name of the system table, such as `QUERY_EXECUTIONS`.
     *
     * @var SystemTableType::*
     */
    private $tableName;

    /**
     * Indicates whether the system table is enabled for the account.
     *
     * @var bool
     */
    private $enabled;

    /**
     * @param array{
     *   TableName: SystemTableType::*,
     *   Enabled: bool,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->tableName = $input['TableName'] ?? $this->throwException(new InvalidArgument('Missing required field "TableName".'));
        $this->enabled = $input['Enabled'] ?? $this->throwException(new InvalidArgument('Missing required field "Enabled".'));
    }

    /**
     * @param array{
     *   TableName: SystemTableType::*,
     *   Enabled: bool,
     * }|SystemTableStatus $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getEnabled(): bool
    {
        return $this->enabled;
    }

    /**
     * @return SystemTableType::*
     */
    public function getTableName(): string
    {
        return $this->tableName;
    }

    /**
     * @return never
     */
    private function throwException(\Throwable $exception)
    {
        throw $exception;
    }
}
