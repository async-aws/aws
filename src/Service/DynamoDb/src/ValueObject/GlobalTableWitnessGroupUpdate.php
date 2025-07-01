<?php

namespace AsyncAws\DynamoDb\ValueObject;

/**
 * Represents one of the following:
 *
 * - A new witness to be added to a new global table.
 * - An existing witness to be removed from an existing global table.
 *
 * You can configure one witness per MRSC global table.
 */
final class GlobalTableWitnessGroupUpdate
{
    /**
     * Specifies a witness Region to be added to a new MRSC global table. The witness must be added when creating the MRSC
     * global table.
     *
     * @var CreateGlobalTableWitnessGroupMemberAction|null
     */
    private $create;

    /**
     * Specifies a witness Region to be removed from an existing global table. Must be done in conjunction with removing a
     * replica. The deletion of both a witness and replica converts the remaining replica to a single-Region DynamoDB table.
     *
     * @var DeleteGlobalTableWitnessGroupMemberAction|null
     */
    private $delete;

    /**
     * @param array{
     *   Create?: null|CreateGlobalTableWitnessGroupMemberAction|array,
     *   Delete?: null|DeleteGlobalTableWitnessGroupMemberAction|array,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->create = isset($input['Create']) ? CreateGlobalTableWitnessGroupMemberAction::create($input['Create']) : null;
        $this->delete = isset($input['Delete']) ? DeleteGlobalTableWitnessGroupMemberAction::create($input['Delete']) : null;
    }

    /**
     * @param array{
     *   Create?: null|CreateGlobalTableWitnessGroupMemberAction|array,
     *   Delete?: null|DeleteGlobalTableWitnessGroupMemberAction|array,
     * }|GlobalTableWitnessGroupUpdate $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getCreate(): ?CreateGlobalTableWitnessGroupMemberAction
    {
        return $this->create;
    }

    public function getDelete(): ?DeleteGlobalTableWitnessGroupMemberAction
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
