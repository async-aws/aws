<?php

namespace AsyncAws\Core\Sts\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * The request was rejected because the session token exceeded the maximum allowed size. The error message reports the
 * session token size and the maximum allowed size, both in bytes. Session policies and session tags add to the session
 * token size. For more information, see Passing Session Tags in STS [^1] in the *IAM User Guide*.
 *
 * You might receive this error even though you meet the individual session policy and session tag limits. Monitor
 * `SessionTokenUtilization` to track how close the session token is to the maximum allowed size. For more information,
 * see IAM and STS Entity Character Limits [^2] in the *IAM User Guide*.
 *
 * [^1]: https://docs.aws.amazon.com/IAM/latest/UserGuide/id_session-tags.html
 * [^2]: https://docs.aws.amazon.com/IAM/latest/UserGuide/reference_iam-quotas.html#reference_iam-limits-entity-length
 */
final class PackedPolicyTooLargeException extends ClientException
{
}
