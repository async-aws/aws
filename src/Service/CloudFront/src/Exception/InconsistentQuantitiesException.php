<?php

namespace AsyncAws\CloudFront\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * The value of `Quantity` and the size of `Items` don't match.
 */
final class InconsistentQuantitiesException extends ClientException
{
}
