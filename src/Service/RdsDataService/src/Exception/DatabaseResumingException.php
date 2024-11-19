<?php

namespace AsyncAws\RdsDataService\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * A request was canceled because the Aurora Serverless v2 DB instance was in a paused state. The Data API request
 * automatically causes the DB instance to begin resuming. Wait a few seconds and try again.
 */
final class DatabaseResumingException extends ClientException
{
}
