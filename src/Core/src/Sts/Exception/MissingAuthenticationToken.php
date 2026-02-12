<?php

namespace AsyncAws\Core\Sts\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * The request was rejected because no authentication token was provided. Login with an identity provider to retrieve
 * an authentication token and then retry the request. For SSO authentication, this often means running `aws sso login`.
 */
final class MissingAuthenticationToken extends ClientException
{
}
