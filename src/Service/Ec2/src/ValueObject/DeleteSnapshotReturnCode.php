<?php

namespace AsyncAws\Ec2\ValueObject;

use AsyncAws\Ec2\Enum\SnapshotReturnCodes;

/**
 * The snapshot ID and its deletion result code.
 */
final class DeleteSnapshotReturnCode
{
    /**
     * The ID of the snapshot.
     *
     * @var string|null
     */
    private $snapshotId;

    /**
     * The result code from the snapshot deletion attempt. Possible values:
     *
     * - `success` - The snapshot was successfully deleted.
     * - `skipped` - The snapshot was not deleted because it's associated with other AMIs.
     * - `missing-permissions` - The snapshot was not deleted because the role lacks `DeleteSnapshot` permissions. For more
     *   information, see How Amazon EBS works with IAM [^1].
     * - `internal-error` - The snapshot was not deleted due to a server error.
     * - `client-error` - The snapshot was not deleted due to a client configuration error.
     *
     * For details about an error, check the `DeleteSnapshot` event in the CloudTrail event history. For more information,
     * see View event history [^2] in the *Amazon Web Services CloudTrail User Guide*.
     *
     * [^1]: https://docs.aws.amazon.com/ebs/latest/userguide/security_iam_service-with-iam.html
     * [^2]: https://docs.aws.amazon.com/awscloudtrail/latest/userguide/tutorial-event-history.html
     *
     * @var SnapshotReturnCodes::*|null
     */
    private $returnCode;

    /**
     * @param array{
     *   SnapshotId?: string|null,
     *   ReturnCode?: SnapshotReturnCodes::*|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->snapshotId = $input['SnapshotId'] ?? null;
        $this->returnCode = $input['ReturnCode'] ?? null;
    }

    /**
     * @param array{
     *   SnapshotId?: string|null,
     *   ReturnCode?: SnapshotReturnCodes::*|null,
     * }|DeleteSnapshotReturnCode $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return SnapshotReturnCodes::*|null
     */
    public function getReturnCode(): ?string
    {
        return $this->returnCode;
    }

    public function getSnapshotId(): ?string
    {
        return $this->snapshotId;
    }
}
