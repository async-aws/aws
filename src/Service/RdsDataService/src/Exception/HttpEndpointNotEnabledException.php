<?php

namespace AsyncAws\RdsDataService\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * The HTTP endpoint for using RDS Data API isn't enabled for the DB cluster.
 */
final class HttpEndpointNotEnabledException extends ClientException
{
}
