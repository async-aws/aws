<?php

namespace AsyncAws\CodeBuild\Enum;

/**
 * Specifies the bucket owner's access for objects that another account uploads to their Amazon S3 bucket. By default,
 * only the account that uploads the objects to the bucket has access to these objects. This property allows you to give
 * the bucket owner access to these objects.
 *
 * > To use this property, your CodeBuild service role must have the `s3:PutBucketAcl` permission. This permission
 * > allows CodeBuild to modify the access control list for the bucket.
 *
 * This property can be one of the following values:
 *
 * - `NONE`:
 *
 *   The bucket owner does not have access to the objects. This is the default.
 * - `READ_ONLY`:
 *
 *   The bucket owner has read-only access to the objects. The uploading account retains ownership of the objects.
 * - `FULL`:
 *
 *   The bucket owner has full access to the objects. Object ownership is determined by the following criteria:
 *
 *   - If the bucket is configured with the **Bucket owner preferred** setting, the bucket owner owns the objects. The
 *     uploading account will have object access as specified by the bucket's policy.
 *   - Otherwise, the uploading account retains ownership of the objects.
 *
 *   For more information about Amazon S3 object ownership, see Controlling ownership of uploaded objects using S3
 *   Object Ownership [^1] in the *Amazon Simple Storage Service User Guide*.
 *
 * [^1]: https://docs.aws.amazon.com/AmazonS3/latest/userguide/about-object-ownership.html
 */
final class BucketOwnerAccess
{
    public const FULL = 'FULL';
    public const NONE = 'NONE';
    public const READ_ONLY = 'READ_ONLY';

    public static function exists(string $value): bool
    {
        return isset([
            self::FULL => true,
            self::NONE => true,
            self::READ_ONLY => true,
        ][$value]);
    }
}
