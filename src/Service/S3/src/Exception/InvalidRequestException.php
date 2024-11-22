<?php

namespace AsyncAws\S3\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * You may receive this error in multiple cases. Depending on the reason for the error, you may receive one of the
 * messages below:
 *
 * - Cannot specify both a write offset value and user-defined object metadata for existing objects.
 * - Checksum Type mismatch occurred, expected checksum Type: sha1, actual checksum Type: crc32c.
 * - Request body cannot be empty when 'write offset' is specified.
 */
final class InvalidRequestException extends ClientException
{
}
