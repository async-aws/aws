<?php

namespace AsyncAws\RdsDataService\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * A request was cancelled because the Aurora Serverless v2 DB instance was paused. The Data API request automatically
 * resumes the DB instance. Wait a few seconds and try again.
 */
final class DatabaseResumingException extends ClientException
{
}
