<?php

namespace AsyncAws\RdsDataService\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * The transaction ID wasn't found.
 */
final class TransactionNotFoundException extends ClientException
{
}
