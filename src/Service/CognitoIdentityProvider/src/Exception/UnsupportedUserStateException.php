<?php

namespace AsyncAws\CognitoIdentityProvider\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * The request failed because the user is in an unsupported state.
 */
final class UnsupportedUserStateException extends ClientException
{
}
