<?php

namespace AsyncAws\DynamoDb\ValueObject;

/**
 * A vector index to be added to or removed from a table.
 */
final class VectorIndexUpdate
{
    /**
     * The configuration for creating a new vector index on the table.
     *
     * @var CreateVectorIndexAction|null
     */
    private $create;

    /**
     * The configuration for deleting an existing vector index from the table.
     *
     * @var DeleteVectorIndexAction|null
     */
    private $delete;

    /**
     * @param array{
     *   Create?: CreateVectorIndexAction|array|null,
     *   Delete?: DeleteVectorIndexAction|array|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->create = isset($input['Create']) ? CreateVectorIndexAction::create($input['Create']) : null;
        $this->delete = isset($input['Delete']) ? DeleteVectorIndexAction::create($input['Delete']) : null;
    }

    /**
     * @param array{
     *   Create?: CreateVectorIndexAction|array|null,
     *   Delete?: DeleteVectorIndexAction|array|null,
     * }|VectorIndexUpdate $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getCreate(): ?CreateVectorIndexAction
    {
        return $this->create;
    }

    public function getDelete(): ?DeleteVectorIndexAction
    {
        return $this->delete;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->create) {
            $payload['Create'] = $v->requestBody();
        }
        if (null !== $v = $this->delete) {
            $payload['Delete'] = $v->requestBody();
        }

        return $payload;
    }
}
