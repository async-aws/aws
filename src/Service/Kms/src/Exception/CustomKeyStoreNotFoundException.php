<?php

namespace AsyncAws\Kms\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * The request was rejected because KMS cannot find a custom key store with the specified key store name or ID.
 */
final class CustomKeyStoreNotFoundException extends ClientException
{
}
