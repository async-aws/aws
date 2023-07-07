<?php

namespace AsyncAws\Kms\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * The request was rejected because the external key store proxy could not find the external key. This exception is
 * thrown when the value of the `XksKeyId` parameter doesn't identify a key in the external key manager associated with
 * the external key proxy.
 *
 * Verify that the `XksKeyId` represents an existing key in the external key manager. Use the key identifier that the
 * external key store proxy uses to identify the key. For details, see the documentation provided with your external key
 * store proxy or key manager.
 */
final class XksKeyNotFoundException extends ClientException
{
}
