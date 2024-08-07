<?php

namespace AsyncAws\CognitoIdentityProvider\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * The message returned when a user's new password matches a previous password and doesn't comply with the
 * password-history policy.
 */
final class PasswordHistoryPolicyViolationException extends ClientException
{
}
