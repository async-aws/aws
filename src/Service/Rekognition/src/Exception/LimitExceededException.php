<?php

namespace AsyncAws\Rekognition\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * An Amazon Rekognition service limit was exceeded. For example, if you start too many jobs concurrently, subsequent
 * calls to start operations (ex: `StartLabelDetection`) will raise a `LimitExceededException` exception (HTTP status
 * code: 400) until the number of concurrently running jobs is below the Amazon Rekognition service limit.
 */
final class LimitExceededException extends ClientException
{
}
