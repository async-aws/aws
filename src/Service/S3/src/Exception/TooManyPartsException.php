<?php

namespace AsyncAws\S3\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * You have attempted to add more parts than the maximum of 10000 that are allowed for this object. You can use the
 * CopyObject operation to copy this object to another and then add more data to the newly copied object.
 */
final class TooManyPartsException extends ClientException
{
}
