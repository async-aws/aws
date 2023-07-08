<?php

namespace AsyncAws\CognitoIdentityProvider\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * This exception is thrown when there is a code mismatch and the service fails to configure the software token TOTP
 * multi-factor authentication (MFA).
 */
final class EnableSoftwareTokenMFAException extends ClientException
{
}
