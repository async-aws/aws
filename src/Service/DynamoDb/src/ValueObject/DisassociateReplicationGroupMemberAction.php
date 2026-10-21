<?php

namespace AsyncAws\DynamoDb\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\DynamoDb\Enum\DisassociationMode;

/**
 * The parameters for disassociating the local replica from a global table using Version 2019.11.21 with multi-Region
 * eventual consistency (MREC).
 *
 * After disassociation completes, the replica remains a standalone regional table that keeps its data, indexes, and
 * table settings.
 *
 * For request requirements, see UpdateTable [^1]. For prerequisites, the protection update, and completion checks, see
 * Disassociating a global table replica [^2] in the *Amazon DynamoDB Developer Guide*.
 *
 * [^1]: https://docs.aws.amazon.com/amazondynamodb/latest/APIReference/API_UpdateTable.html
 * [^2]: https://docs.aws.amazon.com/amazondynamodb/latest/developerguide/globaltables-disassociate.html
 */
final class DisassociateReplicationGroupMemberAction
{
    /**
     * The Region of the replica to disassociate. Set this value to the Region where you send the `UpdateTable` request. You
     * must use credentials for the account that owns the replica.
     *
     * @var string
     */
    private $regionName;

    /**
     * The mode for handling pending replication before disassociation completes. You must specify `WAIT_FOR_REPLICATION`.
     * For more information, see Disassociating a global table replica [^1] in the *Amazon DynamoDB Developer Guide*.
     *
     * [^1]: https://docs.aws.amazon.com/amazondynamodb/latest/developerguide/globaltables-disassociate.html
     *
     * @var DisassociationMode::*
     */
    private $disassociationMode;

    /**
     * @param array{
     *   RegionName: string,
     *   DisassociationMode: DisassociationMode::*,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->regionName = $input['RegionName'] ?? $this->throwException(new InvalidArgument('Missing required field "RegionName".'));
        $this->disassociationMode = $input['DisassociationMode'] ?? $this->throwException(new InvalidArgument('Missing required field "DisassociationMode".'));
    }

    /**
     * @param array{
     *   RegionName: string,
     *   DisassociationMode: DisassociationMode::*,
     * }|DisassociateReplicationGroupMemberAction $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return DisassociationMode::*
     */
    public function getDisassociationMode(): string
    {
        return $this->disassociationMode;
    }

    public function getRegionName(): string
    {
        return $this->regionName;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        $v = $this->regionName;
        $payload['RegionName'] = $v;
        $v = $this->disassociationMode;
        if (!DisassociationMode::exists($v)) {
            /** @psalm-suppress NoValue */
            throw new InvalidArgument(\sprintf('Invalid parameter "DisassociationMode" for "%s". The value "%s" is not a valid "DisassociationMode".', __CLASS__, $v));
        }
        $payload['DisassociationMode'] = $v;

        return $payload;
    }

    /**
     * @return never
     */
    private function throwException(\Throwable $exception)
    {
        throw $exception;
    }
}
