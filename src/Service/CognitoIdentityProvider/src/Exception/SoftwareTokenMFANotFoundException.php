<?php

namespace AsyncAws\CognitoIdentityProvider\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * This exception is thrown when the software token time-based one-time password (TOTP) multi-factor authentication
 * (MFA) isn't activated for the user pool.
 */
final class SoftwareTokenMFANotFoundException extends ClientException
{
}
