<?php

namespace AsyncAws\DynamoDb\ValueObject;

/**
 * Represents one of the following:.
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
     */
    private $Update;

    /**
     * The parameters required for creating a global secondary index on an existing table:.
     */
    private $Create;

    /**
     * The name of an existing global secondary index to be removed.
     */
    private $Delete;

    /**
     * @param array{
     *   Update?: null|UpdateGlobalSecondaryIndexAction|array,
     *   Create?: null|CreateGlobalSecondaryIndexAction|array,
     *   Delete?: null|DeleteGlobalSecondaryIndexAction|array,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->Update = isset($input['Update']) ? UpdateGlobalSecondaryIndexAction::create($input['Update']) : null;
        $this->Create = isset($input['Create']) ? CreateGlobalSecondaryIndexAction::create($input['Create']) : null;
        $this->Delete = isset($input['Delete']) ? DeleteGlobalSecondaryIndexAction::create($input['Delete']) : null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getCreate(): ?CreateGlobalSecondaryIndexAction
    {
        return $this->Create;
    }

    public function getDelete(): ?DeleteGlobalSecondaryIndexAction
    {
        return $this->Delete;
    }

    public function getUpdate(): ?UpdateGlobalSecondaryIndexAction
    {
        return $this->Update;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->Update) {
            $payload['Update'] = $v->requestBody();
        }
        if (null !== $v = $this->Create) {
            $payload['Create'] = $v->requestBody();
        }
        if (null !== $v = $this->Delete) {
            $payload['Delete'] = $v->requestBody();
        }

        return $payload;
    }
}
