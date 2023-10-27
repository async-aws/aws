<?php

namespace AsyncAws\Sns\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * The request was rejected because the state of the specified resource isn't valid for this request. For more
 * information, see Key states of Amazon Web Services KMS keys [^1] in the *Key Management Service Developer Guide*.
 *
 * [^1]: https://docs.aws.amazon.com/kms/latest/developerguide/key-state.html
 */
final class KMSInvalidStateException extends ClientException
{
}
