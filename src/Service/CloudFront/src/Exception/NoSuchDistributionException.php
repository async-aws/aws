<?php

namespace AsyncAws\CloudFront\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * The specified distribution does not exist.
 */
final class NoSuchDistributionException extends ClientException
{
}
