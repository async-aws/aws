<?php

namespace AsyncAws\Athena\Enum;

/**
 * The Amazon S3 canned ACL that Athena should specify when storing query results. Currently the only supported canned
 * ACL is `BUCKET_OWNER_FULL_CONTROL`. If a query runs in a workgroup and the workgroup overrides client-side settings,
 * then the Amazon S3 canned ACL specified in the workgroup's settings is used for all queries that run in the
 * workgroup. For more information about Amazon S3 canned ACLs, see Canned ACL in the *Amazon S3 User Guide*.
 *
 * @see https://docs.aws.amazon.com/AmazonS3/latest/userguide/acl-overview.html#canned-acl
 */
final class S3AclOption
{
    public const BUCKET_OWNER_FULL_CONTROL = 'BUCKET_OWNER_FULL_CONTROL';

    public static function exists(string $value): bool
    {
        return isset([
            self::BUCKET_OWNER_FULL_CONTROL => true,
        ][$value]);
    }
}
