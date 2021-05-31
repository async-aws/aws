<?php

namespace AsyncAws\Route53\Enum;

/**
 * The action to perform:.
 *
 * - `CREATE`: Creates a resource record set that has the specified values.
 * - `DELETE`: Deletes a existing resource record set.
 *
 *   ! To delete the resource record set that is associated with a traffic policy instance, use
 *   ! DeleteTrafficPolicyInstance. Amazon Route 53 will delete the resource record set automatically. If you delete the
 *   ! resource record set by using `ChangeResourceRecordSets`, Route 53 doesn't automatically delete the traffic policy
 *   ! instance, and you'll continue to be charged for it even though it's no longer in use.
 *
 * - `UPSERT`: If a resource record set doesn't already exist, Route 53 creates it. If a resource record set does exist,
 *   Route 53 updates it with the values in the request.
 *
 * @see https://docs.aws.amazon.com/Route53/latest/APIReference/API_DeleteTrafficPolicyInstance.html
 */
final class ChangeAction
{
    public const CREATE = 'CREATE';
    public const DELETE = 'DELETE';
    public const UPSERT = 'UPSERT';

    public static function exists(string $value): bool
    {
        return isset([
            self::CREATE => true,
            self::DELETE => true,
            self::UPSERT => true,
        ][$value]);
    }
}
