<?php

namespace AsyncAws\CloudFormation\ValueObject;

use AsyncAws\CloudFormation\Enum\OperationType;

/**
 * Contains information about a CloudFormation operation.
 */
final class OperationEntry
{
    /**
     * The type of operation.
     *
     * @var OperationType::*|null
     */
    private $operationType;

    /**
     * The unique identifier for the operation.
     *
     * @var string|null
     */
    private $operationId;

    /**
     * @param array{
     *   OperationType?: OperationType::*|null,
     *   OperationId?: string|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->operationType = $input['OperationType'] ?? null;
        $this->operationId = $input['OperationId'] ?? null;
    }

    /**
     * @param array{
     *   OperationType?: OperationType::*|null,
     *   OperationId?: string|null,
     * }|OperationEntry $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getOperationId(): ?string
    {
        return $this->operationId;
    }

    /**
     * @return OperationType::*|null
     */
    public function getOperationType(): ?string
    {
        return $this->operationType;
    }
}
