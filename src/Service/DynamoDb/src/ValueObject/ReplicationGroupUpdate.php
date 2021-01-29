<?php

namespace AsyncAws\DynamoDb\ValueObject;

/**
 * Represents one of the following:.
 *
 * - A new replica to be added to an existing regional table or global table. This request invokes the
 *   `CreateTableReplica` action in the destination Region.
 * - New parameters for an existing replica. This request invokes the `UpdateTable` action in the destination Region.
 * - An existing replica to be deleted. The request invokes the `DeleteTableReplica` action in the destination Region,
 *   deleting the replica and all if its items in the destination Region.
 */
final class ReplicationGroupUpdate
{
    /**
     * The parameters required for creating a replica for the table.
     */
    private $create;

    /**
     * The parameters required for updating a replica for the table.
     */
    private $update;

    /**
     * The parameters required for deleting a replica for the table.
     */
    private $delete;

    /**
     * @param array{
     *   Create?: null|CreateReplicationGroupMemberAction|array,
     *   Update?: null|UpdateReplicationGroupMemberAction|array,
     *   Delete?: null|DeleteReplicationGroupMemberAction|array,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->create = isset($input['Create']) ? CreateReplicationGroupMemberAction::create($input['Create']) : null;
        $this->update = isset($input['Update']) ? UpdateReplicationGroupMemberAction::create($input['Update']) : null;
        $this->delete = isset($input['Delete']) ? DeleteReplicationGroupMemberAction::create($input['Delete']) : null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getCreate(): ?CreateReplicationGroupMemberAction
    {
        return $this->create;
    }

    public function getDelete(): ?DeleteReplicationGroupMemberAction
    {
        return $this->delete;
    }

    public function getUpdate(): ?UpdateReplicationGroupMemberAction
    {
        return $this->update;
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
        if (null !== $v = $this->update) {
            $payload['Update'] = $v->requestBody();
        }
        if (null !== $v = $this->delete) {
            $payload['Delete'] = $v->requestBody();
        }

        return $payload;
    }
}
