<?php

namespace AsyncAws\DynamoDb\ValueObject;

/**
 * Represents one of the following:
 *
 * - A new global secondary index to be added to an existing table.
 * - New provisioned throughput parameters for an existing global secondary index.
 * - An existing global secondary index to be removed from an existing table.
 */
final class GlobalSecondaryIndexUpdate
{
    /**
     * The name of an existing global secondary index, along with new provisioned throughput settings to be applied to that
     * index.
     *
     * @var UpdateGlobalSecondaryIndexAction|null
     */
    private $update;

    /**
     * The parameters required for creating a global secondary index on an existing table:
     *
     * - `IndexName `
     * - `KeySchema `
     * - `AttributeDefinitions `
     * - `Projection `
     * - `ProvisionedThroughput `
     *
     * @var CreateGlobalSecondaryIndexAction|null
     */
    private $create;

    /**
     * The name of an existing global secondary index to be removed.
     *
     * @var DeleteGlobalSecondaryIndexAction|null
     */
    private $delete;

    /**
     * @param array{
     *   Update?: UpdateGlobalSecondaryIndexAction|array|null,
     *   Create?: CreateGlobalSecondaryIndexAction|array|null,
     *   Delete?: DeleteGlobalSecondaryIndexAction|array|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->update = isset($input['Update']) ? UpdateGlobalSecondaryIndexAction::create($input['Update']) : null;
        $this->create = isset($input['Create']) ? CreateGlobalSecondaryIndexAction::create($input['Create']) : null;
        $this->delete = isset($input['Delete']) ? DeleteGlobalSecondaryIndexAction::create($input['Delete']) : null;
    }

    /**
     * @param array{
     *   Update?: UpdateGlobalSecondaryIndexAction|array|null,
     *   Create?: CreateGlobalSecondaryIndexAction|array|null,
     *   Delete?: DeleteGlobalSecondaryIndexAction|array|null,
     * }|GlobalSecondaryIndexUpdate $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getCreate(): ?CreateGlobalSecondaryIndexAction
    {
        return $this->create;
    }

    public function getDelete(): ?DeleteGlobalSecondaryIndexAction
    {
        return $this->delete;
    }

    public function getUpdate(): ?UpdateGlobalSecondaryIndexAction
    {
        return $this->update;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->update) {
            $payload['Update'] = $v->requestBody();
        }
        if (null !== $v = $this->create) {
            $payload['Create'] = $v->requestBody();
        }
        if (null !== $v = $this->delete) {
            $payload['Delete'] = $v->requestBody();
        }

        return $payload;
    }
}
