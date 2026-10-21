<?php

namespace AsyncAws\DynamoDb\ValueObject;

/**
 * Represents one of the following:
 *
 * - A new replica to be added to an existing regional table or global table. This request invokes the
 *   `CreateTableReplica` action in the destination Region.
 * - New parameters for an existing replica. This request invokes the `UpdateTable` action in the destination Region.
 * - An existing replica to be deleted. The request invokes the `DeleteTableReplica` action in the destination Region,
 *   deleting the replica and all if its items in the destination Region.
 * - The local replica to be disassociated from a global table and retained as a standalone regional table.
 *
 * Each `ReplicationGroupUpdate` contains exactly one action. A `Disassociate` action must be the only replica update in
 * the request; including other updates causes the request to fail.
 *
 * > When you manually remove a table or global table replica, you do not automatically remove any associated scalable
 * > targets, scaling policies, or CloudWatch alarms.
 */
final class ReplicationGroupUpdate
{
    /**
     * The parameters required for creating a replica for the table.
     *
     * @var CreateReplicationGroupMemberAction|null
     */
    private $create;

    /**
     * The parameters required for updating a replica for the table.
     *
     * @var UpdateReplicationGroupMemberAction|null
     */
    private $update;

    /**
     * The parameters required for deleting a replica for the table.
     *
     * @var DeleteReplicationGroupMemberAction|null
     */
    private $delete;

    /**
     * The parameters for disassociating the local replica from the global table and retaining it as a standalone regional
     * table.
     *
     * @var DisassociateReplicationGroupMemberAction|null
     */
    private $disassociate;

    /**
     * @param array{
     *   Create?: CreateReplicationGroupMemberAction|array|null,
     *   Update?: UpdateReplicationGroupMemberAction|array|null,
     *   Delete?: DeleteReplicationGroupMemberAction|array|null,
     *   Disassociate?: DisassociateReplicationGroupMemberAction|array|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->create = isset($input['Create']) ? CreateReplicationGroupMemberAction::create($input['Create']) : null;
        $this->update = isset($input['Update']) ? UpdateReplicationGroupMemberAction::create($input['Update']) : null;
        $this->delete = isset($input['Delete']) ? DeleteReplicationGroupMemberAction::create($input['Delete']) : null;
        $this->disassociate = isset($input['Disassociate']) ? DisassociateReplicationGroupMemberAction::create($input['Disassociate']) : null;
    }

    /**
     * @param array{
     *   Create?: CreateReplicationGroupMemberAction|array|null,
     *   Update?: UpdateReplicationGroupMemberAction|array|null,
     *   Delete?: DeleteReplicationGroupMemberAction|array|null,
     *   Disassociate?: DisassociateReplicationGroupMemberAction|array|null,
     * }|ReplicationGroupUpdate $input
     */
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

    public function getDisassociate(): ?DisassociateReplicationGroupMemberAction
    {
        return $this->disassociate;
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
        if (null !== $v = $this->disassociate) {
            $payload['Disassociate'] = $v->requestBody();
        }

        return $payload;
    }
}
