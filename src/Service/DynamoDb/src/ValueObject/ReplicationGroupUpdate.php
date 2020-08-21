<?php

namespace AsyncAws\DynamoDb\ValueObject;

final class ReplicationGroupUpdate
{
    /**
     * The parameters required for creating a replica for the table.
     */
    private $Create;

    /**
     * The parameters required for updating a replica for the table.
     */
    private $Update;

    /**
     * The parameters required for deleting a replica for the table.
     */
    private $Delete;

    /**
     * @param array{
     *   Create?: null|CreateReplicationGroupMemberAction|array,
     *   Update?: null|UpdateReplicationGroupMemberAction|array,
     *   Delete?: null|DeleteReplicationGroupMemberAction|array,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->Create = isset($input['Create']) ? CreateReplicationGroupMemberAction::create($input['Create']) : null;
        $this->Update = isset($input['Update']) ? UpdateReplicationGroupMemberAction::create($input['Update']) : null;
        $this->Delete = isset($input['Delete']) ? DeleteReplicationGroupMemberAction::create($input['Delete']) : null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getCreate(): ?CreateReplicationGroupMemberAction
    {
        return $this->Create;
    }

    public function getDelete(): ?DeleteReplicationGroupMemberAction
    {
        return $this->Delete;
    }

    public function getUpdate(): ?UpdateReplicationGroupMemberAction
    {
        return $this->Update;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->Create) {
            $payload['Create'] = $v->requestBody();
        }
        if (null !== $v = $this->Update) {
            $payload['Update'] = $v->requestBody();
        }
        if (null !== $v = $this->Delete) {
            $payload['Delete'] = $v->requestBody();
        }

        return $payload;
    }
}
