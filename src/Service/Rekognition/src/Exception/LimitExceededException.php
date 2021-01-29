<?php

namespace AsyncAws\Rekognition\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * An Amazon Rekognition service limit was exceeded. For example, if you start too many Amazon Rekognition Video jobs
 * concurrently, calls to start operations (`StartLabelDetection`, for example) will raise a `LimitExceededException`
 * exception (HTTP status code: 400) until the number of concurrently running jobs is below the Amazon Rekognition
 * service limit.
 */
final class LimitExceededException extends ClientException
{
}
