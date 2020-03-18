<?php

namespace AsyncAws\DynamoDb\ValueObject;

class GlobalSecondaryIndexUpdate
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
     *   Update?: null|\AsyncAws\DynamoDb\ValueObject\UpdateGlobalSecondaryIndexAction|array,
     *   Create?: null|\AsyncAws\DynamoDb\ValueObject\CreateGlobalSecondaryIndexAction|array,
     *   Delete?: null|\AsyncAws\DynamoDb\ValueObject\DeleteGlobalSecondaryIndexAction|array,
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
