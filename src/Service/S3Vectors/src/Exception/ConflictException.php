<?php

namespace AsyncAws\S3Vectors\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * The request failed because a vector bucket name or a vector index name already exists. Vector bucket names must be
 * unique within your Amazon Web Services account for each Amazon Web Services Region. Vector index names must be unique
 * within your vector bucket. Choose a different vector bucket name or vector index name, and try again.
 */
final class ConflictException extends ClientException
{
}
