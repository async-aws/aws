<?php

namespace AsyncAws\DynamoDb\ValueObject;

/**
 * A list of requests that can perform update, put, delete, or check operations on multiple items in one or more tables
 * atomically.
 */
final class TransactWriteItem
{
    /**
     * A request to perform a check item operation.
     *
     * @var ConditionCheck|null
     */
    private $conditionCheck;

    /**
     * A request to perform a `PutItem` operation.
     *
     * @var Put|null
     */
    private $put;

    /**
     * A request to perform a `DeleteItem` operation.
     *
     * @var Delete|null
     */
    private $delete;

    /**
     * A request to perform an `UpdateItem` operation.
     *
     * @var Update|null
     */
    private $update;

    /**
     * @param array{
     *   ConditionCheck?: ConditionCheck|array|null,
     *   Put?: Put|array|null,
     *   Delete?: Delete|array|null,
     *   Update?: Update|array|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->conditionCheck = isset($input['ConditionCheck']) ? ConditionCheck::create($input['ConditionCheck']) : null;
        $this->put = isset($input['Put']) ? Put::create($input['Put']) : null;
        $this->delete = isset($input['Delete']) ? Delete::create($input['Delete']) : null;
        $this->update = isset($input['Update']) ? Update::create($input['Update']) : null;
    }

    /**
     * @param array{
     *   ConditionCheck?: ConditionCheck|array|null,
     *   Put?: Put|array|null,
     *   Delete?: Delete|array|null,
     *   Update?: Update|array|null,
     * }|TransactWriteItem $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getConditionCheck(): ?ConditionCheck
    {
        return $this->conditionCheck;
    }

    public function getDelete(): ?Delete
    {
        return $this->delete;
    }

    public function getPut(): ?Put
    {
        return $this->put;
    }

    public function getUpdate(): ?Update
    {
        return $this->update;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->conditionCheck) {
            $payload['ConditionCheck'] = $v->requestBody();
        }
        if (null !== $v = $this->put) {
            $payload['Put'] = $v->requestBody();
        }
        if (null !== $v = $this->delete) {
            $payload['Delete'] = $v->requestBody();
        }
        if (null !== $v = $this->update) {
            $payload['Update'] = $v->requestBody();
        }

        return $payload;
    }
}
