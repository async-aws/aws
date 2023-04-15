<?php

namespace AsyncAws\Athena\ValueObject;

use AsyncAws\Athena\Enum\S3AclOption;
use AsyncAws\Core\Exception\InvalidArgument;

/**
 * Indicates that an Amazon S3 canned ACL should be set to control ownership of stored query results. Currently the only
 * supported canned ACL is `BUCKET_OWNER_FULL_CONTROL`. This is a client-side setting. If workgroup settings override
 * client-side settings, then the query uses the ACL configuration that is specified for the workgroup, and also uses
 * the location for storing query results specified in the workgroup. For more information, see
 * WorkGroupConfiguration$EnforceWorkGroupConfiguration and Workgroup Settings Override Client-Side Settings.
 *
 * @see https://docs.aws.amazon.com/athena/latest/ug/workgroups-settings-override.html
 */
final class AclConfiguration
{
    /**
     * The Amazon S3 canned ACL that Athena should specify when storing query results. Currently the only supported canned
     * ACL is `BUCKET_OWNER_FULL_CONTROL`. If a query runs in a workgroup and the workgroup overrides client-side settings,
     * then the Amazon S3 canned ACL specified in the workgroup's settings is used for all queries that run in the
     * workgroup. For more information about Amazon S3 canned ACLs, see Canned ACL in the *Amazon S3 User Guide*.
     *
     * @see https://docs.aws.amazon.com/AmazonS3/latest/userguide/acl-overview.html#canned-acl
     */
    private $s3AclOption;

    /**
     * @param array{
     *   S3AclOption: S3AclOption::*,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->s3AclOption = $input['S3AclOption'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return S3AclOption::*
     */
    public function getS3AclOption(): string
    {
        return $this->s3AclOption;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null === $v = $this->s3AclOption) {
            throw new InvalidArgument(sprintf('Missing parameter "S3AclOption" for "%s". The value cannot be null.', __CLASS__));
        }
        if (!S3AclOption::exists($v)) {
            throw new InvalidArgument(sprintf('Invalid parameter "S3AclOption" for "%s". The value "%s" is not a valid "S3AclOption".', __CLASS__, $v));
        }
        $payload['S3AclOption'] = $v;

        return $payload;
    }
}
