<?php

namespace AsyncAws\Route53\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * The hosted zone contains resource records that are not SOA or NS records.
 */
final class HostedZoneNotEmptyException extends ClientException
{
}
